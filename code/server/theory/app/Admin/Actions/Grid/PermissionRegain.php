<?php

namespace App\Admin\Actions\Grid;

use App\Admin\Models\Permission;
use Dcat\Admin\Actions\Response;
use Dcat\Admin\Grid\RowAction;
use Dcat\Admin\Traits\HasPermissions;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class PermissionRegain extends RowAction
{
    /**
     * @return string
     */
	protected $title = '收回权限';

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
        $per = Permission::query()->find($id);
        $per['activated'] = false;
        $per->save();

        return $this->response()
            ->success('Regain successfully')
            ->refresh();
    }

    /**
	 * @return string|array|void
	 */
	public function confirm()
	{
        return ['你确定要收回该用户权限吗？'];
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
<a {$this->formatHtmlAttributes()}><i class="feather icon-x-circle"></i> {$this->title()}</a>
HTML;
    }
}
