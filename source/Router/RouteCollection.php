<?php

namespace FlightsTracker\Router;

/**
 * Klasa zawiera kolekcję elementów klasy Route.
 * @package RacyMind\MVCWPraktyce\Engine\Router
 * @author Łukasz Socha <kontakt@lukasz-socha.pl>
 * @version 1.0
 */
class RouteCollection
{
    /**
     * @var array Tablica obiektów klasy Route
     */
    protected $items;

    /**
     * Dodaje obiekt Route do kolekcji
     * @param string $name Nazwa elementu
     * @param Route $item Obiekt Route
     */
    public function add($name, $item)
    {
        $this->items[$name] = $item;
    }

    public function get($name)
    {
        if (array_key_exists($name, $this->items)) {
            return $this->items[$name];
        } else {
            return null;
        }
    }

    /**
     * Zwraca wszystkie obiekty kolekcji
     * @return array array
     */
    public function getAll()
    {
        return $this->items;
    }
} 