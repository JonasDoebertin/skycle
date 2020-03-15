<?php

namespace App\Rules;

use App\Base\Models\Cleaner;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OwnedCleaner implements Rule
{
    /**
     * @var string
     */
    protected const PRIMARY_KEY = 'id';

    /**
     * @var string
     */
    protected const FOREIGN_KEY = 'user_id';

    /**
     * @var string
     */
    protected $table;

    /**
     * @var int
     */
    protected $userId;

    /**
     * Create a new rule instance.
     */
    public function __construct()
    {
        $this->table = (new Cleaner())->getTable();
        $this->userId = auth()->id();
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $id = (int) Str::afterLast($attribute, '.');

        return DB::table($this->table)->where([
            self::PRIMARY_KEY => $id,
            self::FOREIGN_KEY => $this->userId,
        ])->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'You must be the owner of the :attribute.';
    }
}
