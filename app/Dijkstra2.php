<?php
namespace App;
use SplPriorityQueue;
use SplStack;
/**
 * Created by PhpStorm.
 * User: Jonnt Nguyen
 * Date: 10/25/2016
 * Time: 1:31 PM
 */
class Dijkstra2
{
    protected $graph;

    public function __construct($graph) {
        $this->graph = $graph;
    }
    //kich ban ban dau :- source=3, des=4, distance=graph

    public function shortestPath($source, $target) {
        $d = array();
        $pi = array();
        $Q = new SplPriorityQueue();
        //them vao hang cho cac gia tri cua mang distances.
        foreach ($this->graph as $v => $adj) {
            //thuc hien gan gia tri cho tung bien 
            $d[$v] = INF; //gia tri duoc gan la vo cuc
            $pi[$v] = null; //chua xac dinh gia tri tiep theo 
            foreach ($adj as $w => $cost) {
                // gan diem va gia tri khoang cach cho no.
                $Q->insert($w, $cost);//a[1,1234.5656];
            }
        }
            dd($Q);
        $d[$source] = 0;
        while (!$Q->isEmpty()) {
            // extract min cost
            //chi phi toi thieu cho chuyen di
            $u = $Q->extract();
            if (!empty($this->graph[$u])) {
                // "relax" each adjacent vertex
                foreach ($this->graph[$u] as $v => $cost) {
                    // alternate route length to adjacent neighbor
                    $alt = $d[$u] + $cost;
                    // if alternate route is shorter
                    if ($alt < $d[$v]) {
                        $d[$v] = $alt; // update minimum length to vertex
                        $pi[$v] = $u;  // add neighbor to predecessors
                        //  for vertex
                    }
                }
            }
        }
        $S = new SplStack(); // shortest path with a stack
        $u = $target;
        $dist = 0;
        // traverse from target to source
        while (isset($pi[$u]) && $pi[$u]) {
            $S->push($u);
            $dist += $this->graph[$u][$pi[$u]]; // add distance to predecessor
            $u = $pi[$u];
        }

        // stack will be empty if there is no route back
        if ($S->isEmpty()) {
            echo "No route from $source to $target";
        }
        else {
            $S->push($source);
            echo "$dist-";
            $sep = '';
            foreach ($S as $v) {
                echo $sep, $v;
                $sep = '-';
            }
            echo "\n";
        }
    }
}
