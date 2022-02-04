<?php

namespace FlightsTracker\Router;

class RouteCollection
{
    /** Tablica obiektów klasy Route */
    protected $items;

    /**
     * Dodaje obiekt Route do kolekcji
     * @param string $name Nazwa elementu
     * @param Route $item Obiekt Route
     */
    public function add($name, $item) {
        $this->items[$name] = $item;
    }

    /** Zwraca obiekt kolekcji o danej nazwie */
    public function get($name) {
        if (array_key_exists($name, $this->items)) {
            return $this->items[$name];
        } else {
            return null;
        }
    }

    /** Zwraca wszystkie obiekty kolekcji */
    public function getAll() {
        return $this->items;
    }
} 