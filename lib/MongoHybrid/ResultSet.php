<?php
/**
 * This file is part of the Maya project.
 *
 * (c) Ulrich Badinga - 🅱🅰🅳🅻🅴🅴, https://badinga-ulrich.github.io/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MongoHybrid;


class ResultSet extends \ArrayObject {

    /** Driver */
    protected $driver;

    /** @var array - Collection docs cache */
    protected $cache = [];

    /**
     * Constructor
     * @param $driver
     * @param iterable $documents
     */
    public function __construct($driver, &$documents) {

        $this->driver = $driver;
        $this->cache  = [];

        parent::__construct($documents);
    }

    public function hasOne($collections) {

        foreach ($this as &$doc) {

            foreach ($collections as $fkey => $collection) {

                if (isset($doc[$fkey]) && $doc[$fkey]) {

                    if (!isset($this->cache[$collection][$doc[$fkey]])) {
                        $this->cache[$collection][$doc[$fkey]] = $this->driver->findOneById($collection, $doc[$fkey]);
                    }

                    $doc[$fkey] = $this->cache[$collection][$doc[$fkey]];
                }
            }
        }

    }

    public function hasMany($collections) {

        foreach ($this as &$doc) {

            if (isset($doc['_id'])) {

                foreach ($collections as $collection => $fkey) {

                    $doc[$collection] = $this->driver->find($collection, ['filter' => [$fkey=>$doc['_id']]]);
                }
            }
        }
    }

    public function toArray() {
        return $this->getArrayCopy();
    }

    public function __toString() {
        return json_encode($this->getArrayCopy());
    }
}
