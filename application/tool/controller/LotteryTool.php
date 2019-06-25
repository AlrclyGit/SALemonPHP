<?php
/**
 * Name: 抽奖工具类
 * User: 萧俊介
 * Date: 2018/7/10
 * Time: 下午4:08
 */

namespace app\tool\controller;


use app\tool\model\Lottery;
use app\tool\model\Prize;

class LotteryTool extends BaseTool
{


    /*
     * 查询中奖记录
     */
    public static function getLottery()
    {
        $lottery = Lottery::where('open_id', session('open_id'))
            ->field('id,open_id,create_time,update_time,delete_time',true)
            ->find();
        if ($lottery) {
            return saReturn(0, 'OK', $lottery);
        } else {
            return saReturn(1, '你还没有中过奖呢');
        }
    }


    /*
     * 进行抽奖并入库
     */
    public static function setLottery()
    {
        // 进行抽奖
        $prizeAll = Prize::cache(60)->select();
        $prizeId = LotteryTool::getLotteryID($prizeAll);
        $prize = $prizeAll[$prizeId];

        // 进行奖品判断与入库
        if ($prize['type'] == 0) { // 谢谢参与
            $data = [
                'open_id' => session('open_id'),
                'prize_id' => $prizeId,
                'prize_name' => $prize[$prizeId]['name'],
                'prize_type' => $prize[$prizeId]['type'],
            ];
            Lottery::create($data);
            $returnData = [
                'code' => $prize['type'],
                'name' => $prize['name'],
            ];
            return saReturn(0, '谢谢参与', $returnData);
        } elseif ($prize['type'] == 1) { // 线下实物
            Prize::where('id', $prizeId)->setDec('number');
            $code = saRandomString();
            $data = [
                'open_id' => session('open_id'),
                'prize_id' => $prizeId,
                'prize_name' => $prize['name'],
                'prize_type' => $prize['type'],
                'key' => $code
            ];
            Lottery::create($data);
            $returnData = [
                'code' => $prize['type'],
                'name' => $prize['name'],
                'key' => $code
            ];
            return saReturn(1, '线下实物', $returnData);
        } elseif ($prize['type'] == 2) { // 红包
            Prize::where('id', $prizeId)->setDec('number');
            $bonusT = new BonusTool();
            $money = $bonusT->grant($prize['money']);
            $data = [
                'open_id' => session('open_id'),
                'prize_id' => $prizeId,
                'prize_name' => $prize['name'],
                'prize_type' => $prize['type'],
                'money' => $money
            ];
            Lottery::create($data);
            $returnData = [
                'code' => $prize['type'],
                'name' => $prize['name'],
                'money' => $money
            ];
            return saReturn(2, '红包', $returnData);
        } else {
            return saReturn(0, '抽奖出错了！');
        }
    }


    /*
     * 抽奖核心方法
     */
    public static function getLotteryID($lotteryArray)
    {
        foreach ($lotteryArray as $k => $v) {
            if ($lotteryArray[$k]['number'] == 0 || $lotteryArray[$k]['state'] == 0) {
                $lotteryArray[$k]['chance'] = 0;
            }
        }
        // 实例化一个空数组，用来存放抽奖核心信息。
        $coreArray = [];
        // 把配置文件中的"id(奖品编号)"和"chance(奖品中奖概率)"两项目单独合并为一个数组。
        foreach ($lotteryArray as $key => $value) {
            $coreArray[$value['id']] = $value['chance'];
        }
        // 实例化一个空字符串，用来存放中奖的奖品编号。
        $result = '';
        // 概率数组的总概率
        $coreSum = array_sum($coreArray);
        // 如果总概率为0，则直接返回。
        if ($coreSum === 0) {
            return null;
        }
        // 概率数组循环
        foreach ($coreArray as $id => $chance) {
            //
            $coreNumber = mt_rand(1, $coreSum); // 生成一个1到总概率之间的随机数
            if ($coreNumber <= $chance) {
                $result = $id; // 如果随机数小于当前的概率数，则直接返回当前id。
                break;
            } else { // 否则从总概率中剔除掉当前的概率，继续循环。
                $coreSum = $coreSum - $chance;
            }
        }
        unset($coreArray); //$coreArr数组使用完，销毁。
        return $result; // 返回中奖的奖品编号。
    }

}