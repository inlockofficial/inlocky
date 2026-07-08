namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    // ... [Preserve existing traits, $fillable, etc.] ...

    /**
     * Goal 5: Connect Requests and Orders
     * A product (request) may be converted into a single order.
     */
    public function order(): HasOne
    {
        return $this->hasOne(Order::class);
    }

    /**
     * Relationship to the user who made the request.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
