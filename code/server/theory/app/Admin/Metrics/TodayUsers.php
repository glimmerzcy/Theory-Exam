<?php

namespace App\Admin\Metrics;

use App\Admin\Models\Student;
use Dcat\Admin\Widgets\Metrics\RadialBar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TodayUsers extends RadialBar
{
    protected function init()
    {
        parent::init(); // TODO: Change the autogenerated stub

        $this->title('今日上线人数');
        $this->height(250);
        $this->chartHeight(200);
    }

    public function handle(Request $request)
    {
        $rec = DB::table('login_records')
            ->whereDate('created_at',date("Y-m-d"))
            ->groupBy('twt_id')
            ->get();
        $rec1 = DB::table('login_records')
            ->whereDate('created_at',date("Y-m-d",strtotime("-1 day")))
            ->groupBy('twt_id')
            ->get();
        $daily = count($rec);
        $yes = count($rec1);
        $this->withContent($daily, $yes);
        if ($yes != 0){
            $this->withChart(($daily-$yes)/$yes*100);
        } else {
            $this->withChart(100);
        }
    }

    /**
     * 设置图表数据.
     *
     * @param int $data
     *
     * @return $this
     */
    public function withChart(int $data)
    {
        if ($data < 0){
            $this->chartLabels('相比昨天下降');
            $this ->chartColors('red');
            return $this->chart([
                'series' => [abs($data)],
            ]);
        }else{
            $this->chartLabels('相比昨天上升');
            return $this->chart([
                'series' => [$data],
            ]);
        }
    }

    /**
     * 卡片内容
     *
     * @param string $content
     *
     * @param $style
     * @param $value
     * @return $this
     */
    public function withContent($content, $value)
    {
        return $this->content(
            <<<HTML
<div class="d-flex flex-column flex-wrap text-center">
    <h1 class="font-lg-2 mt-2 mb-0">&nbsp&nbsp{$content}</h1>
</div>
HTML
        );
    }
}
