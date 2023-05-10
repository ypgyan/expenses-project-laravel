<?php declare(strict_types=1);

namespace App\Enums\Expenses;

use App\Models\Category;
use BenSampo\Enum\Enum;

/**
 * @method static static ALIMENTACAO()
 * @method static static SAUDE()
 * @method static static MORADIA()
 * @method static static TRANSPORTE()
 * @method static static EDUCACAO()
 * @method static static LAZER()
 * @method static static IMPREVISTOS()
 * @method static static OUTROS()
 */
final class Categories extends Enum
{
    const ALIMENTACAO = 'Alimentação';
    const SAUDE = 'Saúde';
    const MORADIA = 'Moradia';
    const TRANSPORTE = 'Transporte';
    const EDUCACAO = 'Educação';
    const LAZER = 'Lazer';
    const IMPREVISTOS = 'Imprevistos';
    const OUTRAS = 'Outras';

    public static function getCategoryId(string $categoryName = self::OUTRAS): int
    {
        return Category::where('name', $categoryName)->first()->id ?? 8;
    }
}
