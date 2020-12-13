<?php

namespace App\Admin\Metrics;

use Dcat\Admin\Admin;
use Dcat\Admin\Widgets\Metrics\Bar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Users extends Bar
{

    /**
     * 初始化卡片内容
     */
    protected function init()
    {
        parent::init();

        $color = Admin::color();

        $dark35 = $color->dark35();

        // 卡片内容宽度
        $this->contentWidth(5, 7);
        // 标题
        $this->title('上线统计');
//        // 设置下拉选项
//        $this->dropdown([
//            '7' => 'Last 7 Days',
//        ]);
        // 设置图表颜色
        $this->chartColors([
            $dark35,
            $dark35,
            $dark35,
            $dark35,
            $dark35,
            $dark35,
            $color->primary()
        ]);
    }

    /**
     * 处理请求
     *
     * @param Request $request
     *
     * @return mixed|void
     */
    public function handle(Request $request)
    {
        $generator = function ($len) {
            for ($i = 0; $i < $len; $i++) {
                $rec2 = function ($num){
                    return DB::table('login_records')
                        ->whereDate('created_at',date("Y-m-d",strtotime("-".$num." days")))
                        ->groupBy('twt_id')
                        ->get();
                };
                yield count($rec2($len-$i-1));

            }
        };

        $thisWeek = DB::table('login_records')
            ->whereDate('created_at','<=',date("Y-m-d"))
            ->whereDate('created_at','>=',date("Y-m-d",strtotime('-6 days')))
            ->groupBy('twt_id')
            ->get();

        $lastWeek = DB::table('login_records')
            ->whereDate('created_at','<=',date("Y-m-d",strtotime('-7 days')))
            ->whereDate('created_at','>=',date("Y-m-d",strtotime('-13 days')))
            ->groupBy('twt_id')
            ->get();
        $countL = count($lastWeek);
        $countT = count($thisWeek);

        switch ($request->get('option')) {
            case '7':
            default:
                // 卡片内容
                if ($countL != 0){
                    $this->withContent($this->KChange(count($thisWeek)), (number_format(($countT-$countL)/$countL*100,1)));
                }else{
                    $this->withContent($this->KChange(count($thisWeek)), 100);

                }

                // 图表数据
                $this->withChart([
                    [
                        'name' => '上线人数',
                        'data' => collect($generator(7))->toArray(),
                    ],
                ]);
        }
    }

    /**
     * 设置图表数据.
     *
     * @param array $data
     *
     * @return $this
     */
    public function withChart(array $data)
    {
        return $this->chart([
            'series' => $data,
        ]);
    }

    /**
     * 设置卡片内容.
     *
     * @param string $title
     * @param string $value
     * @param string $style
     *
     * @return $this
     */
    public function withContent($title, $value, $style = 'success')
    {
        $minus = '+';
        if ($value < 0){
            $style = 'error';
            $minus = null;
        }

        // 根据选项显示
        $label = strtolower(
            $this->dropdown[request()->option] ?? 'last 7 days'
        );

        $minHeight = '183px';

        return $this->content(
            <<<HTML
<div class="d-flex p-1 flex-column justify-content-between" style="padding-top: 0;width: 100%;height: 100%;min-height: {$minHeight}">
    <div class="text-left">
        <h1 class="font-lg-2 mt-2 mb-0">{$title}</h1>
        <h5 class="font-medium-2" style="margin-top: 10px;">
            <span>比上周</span>
            <span class="text-{$style}">{$minus}{$value}%</span>
        </h5>
    </div>
</div>
HTML
        );
    }

    private function KChange($num){
        return $num>10000?number_format($num/1000,1).'K':$num;
    }

}
