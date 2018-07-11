<?php
/**
 * Created by 血界战线委员会.
 * User: 萧俊介
 * Date: 2018/7/10
 * Time: 下午4:08
 */

namespace app\tool\controller;



class LotteryTool extends BaseTool
{

    /*
     * 抽奖核心方法
     */
    static function getLotteryID($lotteryArray)
    {
        foreach ($lotteryArray as $k => $v) {
            if ($lotteryArray[$k]['prize_number'] == 0 || $lotteryArray[$k]['prize_state'] == 0) {
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