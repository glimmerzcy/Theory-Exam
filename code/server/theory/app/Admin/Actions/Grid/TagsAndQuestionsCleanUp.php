<?php

namespace App\Admin\Actions\Grid;

use App\Admin\Models\Paper;
use App\Admin\Models\Question;
use App\Admin\Models\Subjective;
use App\Models\Tag;
use Dcat\Admin\Actions\Response;
use Dcat\Admin\Grid\Tools\AbstractTool;
use Dcat\Admin\Traits\HasPermissions;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class TagsAndQuestionsCleanUp extends AbstractTool
{
    /**
     * @return string
     */
	protected $title = '清理';

    /**
     * Handle the action request.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function handle(Request $request)
    {
        $input = array('is_exist'=>false);
        $papers = Paper::query()->where('is_exist',false)->get();
        $paper_ids = array();
        foreach ($papers as $paper){
            array_push($paper_ids,$paper->id);
        }
        Tag::query()->whereIn('paper',$paper_ids)->update($input);

        $tags = Tag::query()->where('is_exist',false)->get();
        $tag_ids = array();
        foreach ($tags as $tag){
            array_push($tag_ids,$tag->id);
        }
        Question::query()->whereIn('tag',$tag_ids)->update($input);
        Subjective::query()->whereIn('tag',$tag_ids)->update($input);

        return $this->response()
            ->success('Clean up successfully.')
            ->refresh();
    }

    /**
     * @return string|void
     */
    protected function href()
    {
        // return admin_url('auth/users');
    }

    /**
	 * @return string|array|void
	 */
	public function confirm()
	{
        return ['确认清理？'];
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
