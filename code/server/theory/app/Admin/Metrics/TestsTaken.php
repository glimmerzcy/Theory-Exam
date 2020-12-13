<?php

namespace App\Admin\Metrics;

use Dcat\Admin\Widgets\Metrics\Line;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestsTaken extends Line
{
    protected function init()
    {
        parent::init(); // TODO: Change the autogenerated stub

        $this->title('考试次数统计');
        $this->dropdown([
            '7' => 'Last 7 Days',
            '30' => 'Last 30 Days',
        ]);
    }

    public function handle(Request $request)
    {
        $generator = function ($len) {
            for ($i = 0; $i < $len; $i++) {
                yield DB::table('histories')
                    ->whereDate('updated_at',date('Y-m-d', strtotime('-'.($len-$i-1).'days')))
                    ->count();
            }
        };
        $total = function ($len) {
            return DB::table('histories')
                ->whereDate('updated_at','>=',date('Y-m-d', strtotime('-'.($len-1).'days')))
                ->whereDate('updated_at','<=',date('Y-m-d'))
                ->count();
        };

        switch ($request->get('option')){
            case '30':
                $this->withContent($this->KChange($total(30)));
                $this->withChart(collect($generator(30))->toArray());
                break;
            case '7':
            default:
                $this->withContent($this->KChange($total(7)));
                $this->withChart(collect($generator(7))->toArray());
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
            'series' => [
                [
                    'name' => $this->title,
                    'data' => $data,
                ],
            ],
        ]);
    }

    /**
     * 设置卡片内容.
     *
     * @param string $content
     *
     * @return $this
     */
    public function withContent($content)
    {
        return $this->content(
            <<<HTML
<div class="d-flex justify-content-between align-items-center mt-1" style="margin-bottom: 2px">
    <h2 class="ml-1 font-lg-1">{$content}</h2>
</div>
HTML
        );
    }

    private function KChange($num){
        return $num>10000?number_format($num/1000,1).'K':$num;
    }
}
