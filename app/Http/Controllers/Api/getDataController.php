<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Services\FactoryService;
use Illuminate\Support\Facades\DB;
use App\Role;
use App\User;
use App\Dijkstra;
use App\Dijkstra2;
use App\Http\Controllers\Api\DijkstraController;

define('I',1000);
class getDataController extends ApiController
{

    public function __construct()
    {
        $this->service = FactoryService::getUserService();
    }
    public function insertMarker(Request $request)
    {
        $lat=$request['lat'];
        $lon=$request['lon'];
        DB::table('Markers')->insert(
            ['lat' => $lat, 'lng' => $lon]
        );
        return response('add new marker !!!');
    }
    public function showMarker(){

        $count=DB::table('Markers')->where('id','<',25)->get();
        return $count;
    }
    public function allMarkers(){
        $count=DB::table('Markers')->get();
        return $count;
    }

    public function DistanceAllMarkers(Request $request)
    {

        $sour=$request['source_address'];
        $des=$request['des_address'];
        $distance=$request['Distance'];
        DB::table('Distances')->insert(
            ['source_address' => $sour, 'des_address' => $des,'distance'=>$distance]
        );
        return response('add new marker !!!');
    }
    public function CountMarkers(){
        return DB::table('Markers')->get();
    }
    public function Dijkstra(Request $request)
    {
        $source=$request['source_address'];
        $des=$request['des_address'];
        $matrixWidth =$request['matrixWidth'];

// $points is an array in the following format: (router1,router2,distance-between-them)
        $points = $request['array'];
        $temp = explode(":", $points);
        $sets = array();
        foreach ($temp as $value)
        {
            $nums = explode(",", $value);
            $value = array();
            foreach ($nums as $num) {
                array_push($value, (double) $num);
            }
            array_push($sets, $value);
        }
        $ourMap=array();
        for ($i=0,$m=count($sets); $i<$m; $i++) {
            $x = $sets[$i][0];
            $y = $sets[$i][1];
            $c = $sets[$i][2];
            $ourMap[$x][$y] = $c;
            $ourMap[$y][$x] = $c;
        }
        for ($i=0; $i < $matrixWidth; $i++) {
            for ($k=0; $k < $matrixWidth; $k++) {
                if ($i == $k) $ourMap[$i][$k] = 0;
            }
        }
        $dijkstra = new Dijkstra($ourMap,I,$matrixWidth);
        $dijkstra->Dijkstra($ourMap,I);
        $dijkstra->findShortestPath($source);
        return $dijkstra->getResults($des);
    }
    public function PolylineMarkers()
    {
        $array="";
        $contents=DB::table('Distances')->get();
        foreach ($contents as $content)
        {
            $source=$content->source_address;
            $des=$content->des_address;
            $distance=$content->distance;
           $array.= $source.','.$des.','.$distance.':';
        }

        return $array;
    }
    public function Dijkstra2(Request $request)
    {
        $source=$request['source'];
        $des=$request['des'];
        $graph=$request['graph'];
        //$graph=$this->graph();
        //return $graph;
        $g = new Dijkstra2($graph);
        return $g->shortestPath($source,$des);// 3:4->5->3

    }
    public function PolylineMarkers2()
    {
        $arr1=array();
        $arr2=array();
        $count_markers=20;
        $contents=DB::table('Distances')->count();
        for($i=0;$i<$count_markers;$i++)
        {
            
        }
        $content=DB::table('Distances')->get();

       return $contents;
    }
    public function getLat(Request $request)
    {
        $id=$request['id'];
        $contents=DB::table('Markers')->where('id','=',$id)->pluck('lat')[0];
        return floatval($contents);
    }
    public function getLng(Request $request)
    {
        $id=$request['id'];
        $contents=DB::table('Markers')->where('id','=',$id)->pluck('lon')[0];
        return $contents;
    }
    public function DrawStress(Request $request)
    {
        $markers=$request['markers'];
       // $markers=explode(",",$markers);

        $array_marker=array();
        foreach ($markers as $marker){
            $lat=DB::table('Markers')->where('id',$marker+1)->pluck('lat')[0];
            $lng=DB::table('Markers')->where('id',$marker+1)->pluck('lng')[0];
            $taget=$this->convertLatLng((float)$lat,(float)$lng);
            array_push($array_marker,$taget);
        }
        return $array_marker;
    }
    public function convertLatLng($lat,$lng){
        $array = array(
            "lat" => $lat,
            "lng" => $lng
        );
        return $array;
    }
    public function insertDistance(Request $request){
        $marker1=$request['marker1'];
        $marker2=$request['marker2'];
        $sour=$marker1[0];
        $des=$marker2[0];
        $R = 6378137; // Earth’s mean radius in meter
        $dLat = deg2rad($marker2[1] - $marker1[1]);
        $dLong = deg2rad($marker2[2]- $marker1[2]);
        $a = sin($dLat/2)*sin($dLat/2)+cos(deg2rad($marker1[1]))*cos(deg2rad($marker2[1]))*sin($dLong/2)*sin($dLong/2);
        $c = 2 * atan2(sqrt($a),sqrt(1 - $a));
        $d = $R * $c;
//        $latitudeFrom = $marker1[1];
//        $longitudeFrom =$marker1[2];
//        $latitudeTo = $marker1[1];
//        $longitudeTo = $marker1[2];
//        $theta = $longitudeFrom - $longitudeTo;
//        $dist = sin(deg2rad($latitudeFrom)) * sin(deg2rad($latitudeTo)) +  cos(deg2rad($latitudeFrom)) * cos(deg2rad($latitudeTo)) * cos(deg2rad($theta));
//        $dist = acos($dist);
//        $dist = rad2deg($dist);
//        $miles = $dist * 60 * 1.1515;
        DB::table('Distances')->insert(
            ['source_address' => $sour, 'des_address' => $des,'distance'=>$d]
        );
        return response('add new distance !!!');
    }

    public function graph(){
        $team_amounts = [];
        $sources = DB::table('Distances')->select('source_address')->distinct()->orderBy('source_address', 'asc')->get();
        $distances = DB::table('Distances')->get();

        for ($i = 0, $len = count($sources); $i < $len; $i++) {
            $source = $sources[$i];

            $sourceAddress = $source->source_address;

            $team_amounts[$sourceAddress] = $this->groupDesAddress($sourceAddress, $distances);
        }

        
        return $team_amounts;
    }

    private function groupDesAddress($sourceAddress, $distances) {
        $group = [];

        for ($i = 0, $len = count($distances); $i < $len; $i++) {
            $distance = $distances[$i];

            if ($sourceAddress == $distance->source_address) {
                $group[$distance->des_address] = (double) $distance->distance;
            }
        }

        return $group;
    }
    public  function Dijkstra3(Request $request){
        $distances = DB::table('Distances')->get();
        $arrayDistances=array();
        $source=$request['source'];
        $des=$request['des'];
        $a = $source;
        $b = $des;
        foreach ($distances as $distance=>$value){
            $arrayDistances[$value->source_address][$value->des_address]=$value->distance;
        }

        $S = array();//the nearest path with its parent and weight
        $Q = array();//the left nodes without the nearest path
        foreach(array_keys($arrayDistances) as $val) $Q[$val] = 99999;
        $Q[$a] = 0;
        while(!empty($Q)){
            $min = array_search(min($Q), $Q);//the most min weight
            if($min == $b) break;
            foreach($arrayDistances[$min] as $key=>$val) if(!empty($Q[$key]) && $Q[$min] + $val < $Q[$key]) {
                $Q[$key] = $Q[$min] + $val;
                $S[$key] = array($min, $Q[$key]);
            }
            unset($Q[$min]);
        }
        $path = array();
        $pos = $b;
        while($pos != $a){
            $path[] = $pos;
            $pos = $S[$pos][0];
        }
        $path[] = $a;
        $path = array_reverse($path);
//        echo "<br />From $a to $b";
//        echo "<br />The length is ".$S[$b][1];
//        echo "<br />Path is ".implode('->', $path);
        return $path;
    }
}
