<?php
namespace App\Http\Controllers;

use Route;
use Session;
use Illuminate\Http\Request;
use Storage;

/**
 * Class GameController
 * @package App\Http\Controllers
 */
class GameController extends Controller
{
    public $respond = array();//敵人(障礙物)位址座標 enemies position
    public $rowQuantity = 7;//列數 rows
    public $columnQuantity = 7;//欄數 columns
    public $enemyQuantity = 1;//敵人數(障礙物數)enemies number
    public $path = array();//起點至終點的隨機路徑

    public function __construct()
    {

    }

    public function index()
    {
        return view('game.game');
    }

    /**
     * 回傳敵人位址資料給前端
     * @param Request $request
     * @return string
     */
    public function grid(Request $request)
    {
        $this->rowQuantity = $request->input('rowQuantity',7);
        $this->columnQuantity = $request->input('columnQuantity',7);
        $this->enemyQuantity = $request->input('enemyQuantity',1);

        $this->respond=array();
        $this->path=$this->get_random_path();//取得隨機路徑

        for($i=1;$i<=$this->enemyQuantity;$i++){
            $number=$this->get_number();//取得敵人(障礙物)座標位址
            $this->respond[$i-1]['rowIndex']=$number['rowIndex'];
            $this->respond[$i-1]['columnIndex']=$number['columnIndex'];
        }
        return json_encode($this->respond);
    }

    /**
     * 取得起點至終點隨機路徑
     * @return array
     */
    private function get_random_path(){
        $path=array();
        $i=0;
        $j=0;
        $total_step=$this->rowQuantity+$this->columnQuantity;
        for($k=0;$k<$total_step-1;$k++){
            if($i==$this->rowQuantity-1){
                $next_step=2;//only go right
            }elseif($j==$this->columnQuantity-1){
                $next_step=1;//only go down
            }else{
                $next_step=rand(1,2);//1:go down; 2:go right
            }

            if($k!=0){
                if($next_step==1) $i++;
                if($next_step==2) $j++;
            }
            $path[]=array('rowIndex'=>$i,'columnIndex'=>$j);
        }
        return $path;
    }

    /**
     * 取得敵人(障礙物)座標位址
     * @return array
     */
    private function get_number()
    {
        $rowIndex=rand(0,$this->rowQuantity-1);
        $columnIndex=rand(0,$this->columnQuantity-1);
        $result=$this->check_value($rowIndex,$columnIndex);//判斷座標是否合乎規定
        if($result){
            //合乎規定回傳座標
            return array('rowIndex'=>$rowIndex,'columnIndex'=>$columnIndex);
        }else{
            //不合乎規定重新取值
            return $this->get_number();
        }
    }

    /**
     * 判斷敵人(障礙物)座標是否合乎規定,
     * @param $rowIndex
     * @param $columnIndex
     * @return bool
     */
    private function check_value($rowIndex,$columnIndex){
        foreach($this->respond as $val){
            //判斷敵人(障礙物)是否重覆取
            if($rowIndex==$val['rowIndex'] && $columnIndex==$val['columnIndex']) return false;
        }
        foreach($this->path as $path){
            //敵人(障礙物)不可在起點至終點路徑座標上及其上下左右各一格的位置, 否則會擋住路徑
            if($rowIndex==$path['rowIndex']){
                if(in_array($columnIndex,array($path['columnIndex'],$path['columnIndex']+1,$path['columnIndex']-1))){
                    return false;
                }
            }
            if($rowIndex==$path['rowIndex']+1){
                if($columnIndex==$path['columnIndex']) return false;
            }
            if($rowIndex==$path['rowIndex']-1){
                if($columnIndex==$path['columnIndex']) return false;
            }
        }
        return true;
    }
}
