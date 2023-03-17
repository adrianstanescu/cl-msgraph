<?php

declare(strict_types=1);

namespace Adrian\CLMSGraph;

use Adrian\CLMSGraph\Utils\Debug;
use ArrayAccess;
use Countable;
use Iterator;
use Microsoft\Graph\Http\GraphCollectionRequest;
use RuntimeException;

class Collection implements Iterator, Countable, ArrayAccess {
    public const NO_PAGINATION = 1;

    protected static int $pageSize = 10;

    protected GraphCollectionRequest $request;
    protected array $pages = [];
    protected ?int $itemCount = null;
    protected int $position = 0;
    protected string $returnType;
    protected int $flags = 0;

    public function __construct(string $endpoint, $returnType, $flags = 0) {
        $this->flags = $flags;
        if ($this->usePagination()) {
            $endpoint = $endpoint . (strpos($endpoint, '?') === false ? '?' : '&') . '$count=true';
        }
        $this->request = Graph::instance()->createCollectionRequest('GET', $endpoint);
        if ($this->usePagination()) {
            $this->request->setPageSize(Collection::$pageSize);
            $this->request->addHeaders(['ConsistencyLevel' => 'eventual']);
        }

        $this->returnType = $returnType;
    }

    // Countable
    public function count(): int {
        if ($this->itemCount === null) {
            $page = $this->getPage(0);
            if ($this->usePagination()) {
                if (!isset($page['@odata.count'])) {
                    // TODO: get all pages and count
                    throw new RuntimeException('Count unavailable for this collection');
                }
                $this->itemCount = $page['@odata.count'];
            } else {
                $this->itemCount = count($page['value']);
            }
        }

        return $this->itemCount;
    }

    // ArrayAccess
    public function offsetExists($offset): bool {
        if (!is_int($offset) || $offset < 0) {
            return false;
        }
        $pageNumber = (int) ($offset / Collection::$pageSize);
        $page = $this->getPage($pageNumber);
        if ($page === null) {
            return false;
        }
        $pageOffset = $offset % Collection::$pageSize;

        return count($page['value']) >= $pageOffset + 1;
    }

    public function offsetGet($offset) {
        if (!is_int($offset) || $offset < 0) {
            throw new RuntimeException('Invalid Collection offset');
        }

        $pageNumber = (int) ($offset / Collection::$pageSize);
        $page = $this->getPage($pageNumber);
        if ($page === null) {
            throw new RuntimeException('End of collection reached');
        }
        $pageOffset = $offset % Collection::$pageSize;

        return $this->returnObject($page['value'][$pageOffset]);
    }

    public function offsetSet($offset, $value): void {
        throw new RuntimeException('The collection is read only');
    }

    public function offsetUnset($offset): void {
        throw new RuntimeException('The collection is read only');
    }

    // Iterator
    public function rewind(): void {
        $this->position = 0;
    }

    public function current() {
        return $this->offsetGet($this->position);
    }

    public function key(): int {
        return $this->position;
    }

    public function next(): void {
        ++$this->position;
    }

    public function valid(): bool {
        return $this->offsetExists($this->position);
    }

    public static function setPageSize(int $pageSize) {
        Collection::$pageSize = max($pageSize, 1);
    }

    protected function usePagination(): bool {
        if ($this->flags & Collection::NO_PAGINATION) {
            return false;
        }
        // TODO: check if specific endpoint supports $count?
        return true;
    }

    protected function returnObject(array $data) {
        $cls = $this->returnType;

        return new $cls($data);
    }

    protected function getPage($index = 0) {
        if (!isset($this->pages[$index])) {
            for ($i = count($this->pages); $i <= $index; ++$i) {
                if ($this->request->isEnd()) {
                    return null;
                }
                Debug::dump('Request next %s page (%d)', $this->returnType, $i);
                $this->pages[] = $this->request->getPage();
            }
        }

        return $this->pages[$index];
    }
}
