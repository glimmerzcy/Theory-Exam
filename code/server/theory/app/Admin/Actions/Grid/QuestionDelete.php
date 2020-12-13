<?php

namespace App\Admin\Actions\Grid;

use App\Admin\Models\Question;
use Dcat\Admin\Actions\Response;
use Dcat\Admin\Grid\RowAction;
use Dcat\Admin\Traits\HasPermissions;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class QuestionDelete extends RowAction
{
    /**
     * @return string
     */
	protected $title = '';

    /**
     * Handle the action request.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function handle(Request $request)
    {
        // dump($this->getKey());

        $id = $this->getKey();
        $paper = Question::query()->find($id);
        $paper['is_exist'] = false;
        $paper->save();

        return $this->response()
            ->success('Delete successfully')
            ->refresh();
    }

    /**
	 * @return string|array|void
	 */
	public function confirm()
	{
		// return ['Confirm?', 'contents'];
        return ['你确定要删除道题吗？'];
	}

    /**
     * @param Model|Authenticatable|HasPermissions|null $user
     *
     * @return bool
     */
    protected function authorize($user): bool
    {
        return true;
    }

    /**
     * @return array
     */
    protected function parameters()
    {
        return [];
    }

    protected function html()
    {
        return <<<HTML
<a {$this->formatHtmlAttributes()}>
    <i class="feather icon-trash-2"></i> {$this->title()}
</a>
HTML;
    }
}
