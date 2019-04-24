<?php


namespace app\tool\controller;


use app\tool\model\Ranking;
use app\tool\model\UserInfo;

class RankingTool extends BaseTool
{
    /*
     * 写入一个排行
     */
    public static function setRanking()
    {
        // 获取新分数
        $newGrade = input('post.grade');
        // 获取旧分数
        $ranking = Ranking::where('open_id', session('open_id'))->find();
        // 判断
        if ($ranking) { // 分数存在
            if ($newGrade > $ranking['grade']) {
                $ranking->save(['grade' => $newGrade]);
                return saReturn(0, '更新成功', ['grade' => $newGrade]);
            } else {
                return saReturn(1, '新分数还没旧分数高');
            }
        } else { // 分数不存在
            $data = [
                'open_id' => session('open_id'),
                'grade' => $newGrade
            ];
            Ranking::create($data);
            return saReturn(0, '添加成功', $newGrade);
        }
    }


    /*
     * 查询排行
     */
    public static function getRanking($limit = 200, $cache = 15)
    {
        // 世界排行
        $lottery = Ranking::join('user_info', 'ranking.open_id = user_info.open_id')
            ->field('nick_name,head_img_url,grade')
            ->order(['grade desc'])
            ->limit($limit)
            ->cache($cache)
            ->select();
        // 个人排行
        $order = 0;
        for ($i = 0; $i < count($lottery); $i++) {
            if ($lottery[$i]['open_id'] == session('open_id')) {
                $order = $i + 1;
                $grade = $lottery[$i]['grade'];
            }
        }
        // 个人信息
        $userInfo = UserInfo::where('open_id', session('open_id'))->find();
        // 返回数组;
        $data = [
            $lottery,
            $myLottery = [
                "nick_name" => $userInfo['nick_name'],
                "head_img_url" => $userInfo['head_img_url'],
                "ranking" => $order,
                'grade' => $grade
            ]
        ];
        return saReturn(0, 'OK', $data);
    }

}