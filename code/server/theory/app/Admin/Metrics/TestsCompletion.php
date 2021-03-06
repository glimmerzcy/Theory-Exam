<?php

namespace App\Admin\Metrics;

use Dcat\Admin\Widgets\Metrics\Round;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestsCompletion extends Round
{
    protected function init()
    {
        parent::init(); // TODO: Change the autogenerated stub

        $this->title('今日考试完成情况');
        $this->chartLabels(['已完成', '正在考试', '已放弃']);
    }

    public function handle(Request $request)
    {
        $total = DB::table('histories')
            ->whereDate('updated_at',date('Y-m-d'))
            ->count();
        $finished = DB::table('histories')
            ->whereDate('updated_at',date('Y-m-d'))
            ->where('score','!=',0)
            ->count();
        $pending = DB::table('scores')
            ->whereColumn('updated_at', '<', 'started_at')
            ->count();
        $rejected = DB::table('histories')
            ->whereDate('updated_at',date('Y-m-d'))
            ->where('score',0)
            ->count();

        $this->withContent($finished, $pending, $rejected);
        $this->withChart([
            number_format($finished/$total*100,1),
            number_format($pending/$total*100,1),
            number_format($rejected/$total*100,1)]);
        $this->chartTotal('总数', $total);

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
     * 卡片内容.
     *
     * @param int $finished
     * @param int $pending
     * @param int $rejected
     *
     * @return $this
     */
    public function withContent($finished, $pending, $rejected)
    {
        return $this->content(
            <<<HTML
<div class="col-12 d-flex flex-column flex-wrap text-center" style="max-width: 220px">
    <div class="chart-info d-flex justify-content-between mb-1 mt-2" >
          <div class="series-info d-flex align-items-center">
              <i class="fa fa-circle-o text-bold-700 text-primary"></i>
              <span class="text-bold-600 ml-50">已完成</span>
          </div>
          <div class="product-result">
              <span>{$finished}</span>
          </div>
    </div>

    <div class="chart-info d-flex justify-content-between mb-1">
          <div class="series-info d-flex align-items-center">
              <i class="fa fa-circle-o text-bold-700 text-warning"></i>
              <span class="text-bold-600 ml-50">正在考试</span>
          </div>
          <div class="product-result">
              <span>{$pending}</span>
          </div>
    </div>

     <div class="chart-info d-flex justify-content-between mb-1">
          <div class="series-info d-flex align-items-center">
              <i class="fa fa-circle-o text-bold-700 text-danger"></i>
              <span class="text-bold-600 ml-50">已放弃</span>
          </div>
          <div class="product-result">
              <span>{$rejected}</span>
          </div>
    </div>
</div>
HTML
        );
    }

}
