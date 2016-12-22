<?php
namespace App;
use SplPriorityQueue;
use SplStack;

class Dijkstra2
{
    protected $graph;

    public function __construct($graph,$vertexs) {
        $this->graph = $graph;
        $this->vertexs = $vertexs;
    }
    //kich ban ban dau :- source=3, des=4, distance=graph

    public function shortestPath($source, $target) {
       
        $dist = array();
        $previous = array();
        $Q = new SplPriorityQueue();
        
        $graph = $this->graph;
        $vertexs = $this->vertexs;
        //them vao hang cho cac gia tri cua mang distances.
        $dist[$source] = 0;
        foreach ($vertexs as $v) {
            $id = $v->id;
            if ($id != $source ) {
                $dist[$id] = 999999;
                $previous[$id] = null;
            }
            $Q->insert($id, INF-$dist[$id]);
   
        }
        while (!$Q->isEmpty()) {
            $u = $Q->extract();
            if (!empty($this->graph[$u])) {
                // "relax" each adjacent vertex
                foreach (array_keys($this->graph[$u]) as $v) {
                    
                    $alt = $dist[$u] + $this->graph[$u][$v];
                    
                    // if alternate route is shorter
                    if ($alt < $dist[$v]) {
                        $dist[$v] = $alt; // update minimum length to vertex
                        $previous[$v] = $u;  // add neighbor to predecessors
                        $Q->insert($v, INF-$dist[$v]);
                    }
                }
            }
        }

        $S = new SplStack(); // shortest path with a stack
        $u = $target;
        $dist = 0;
        // traverse from target to source
        while (isset($previous[$u]) && $previous[$u]) {
            $S->push($u);
            $dist += $this->graph[$previous[$u]][$u]; // add distance to predecessor
            $u = $previous[$u];
        }

        // stack will be empty if there is no route back
        if ($S->isEmpty()) {
            return "No route from $source to $target";
        }
        else {
            $string = "";
            $S->push($source);
            $string =  $string."$dist-";
            $sep = '';
            foreach ($S as $v) {
                $string =  $string."$sep,".$v;
                $sep = '-';
            }
            return $string;
        }
    }
}
