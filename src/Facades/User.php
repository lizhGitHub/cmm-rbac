<?php
/**
 * User: lzh
 * Date: 2020/9/4
 * Time: 11:36
 */

namespace CMM\RBAC\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class User
 * @package CMM\RBAC\Facades
 *
 * @method static array user()
 * @method static array roleList()
 * @method static array users()
 */
class User extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'rbac.user';
    }
}
