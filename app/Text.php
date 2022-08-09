<?php

/**
 * Created by Reliese Model.
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Text
 *
 * @property int $id
 * @property string $imagem
 * @property string|null $titulo
 * @property string|null $descricao
 * @property string|null $slug
 * @property int $cmsuser_id
 * @property timestamp $created_at
 * @property timestamp $updated_at
 *
 * @property CmsUser $cms_user
 *
 * @package App\Models
 */
class Text extends Model
{
	protected $table = 'texts';

	protected $casts = [
		'imagem' => 'string',
		'titulo' => 'string',
		'slug' => 'string'
	];

	protected $fillable = [
		'imagem',
		'titulo',
		'descricao',
		'slug'
	];

	public function cms_user()
	{
		return $this->belongsTo(CmsUser::class, 'cmsuser_id');
	}
}
