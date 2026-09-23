<?php

file_put_contents('database/migrations/2026_09_23_194028_add_logistics_fields_to_users_table.php', '<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table(\'users\', function (Blueprint ) {
            ->string(\'role\')->default(\'customer\');
            ->string(\'phone\')->nullable();
            ->string(\'company_name\')->nullable();
            ->decimal(\'wallet_balance\', 10, 2)->default(0);
        });
    }

    public function down()
    {
        Schema::table(\'users\', function (Blueprint ) {
            ->dropColumn([\'role\', \'phone\', \'company_name\', \'wallet_balance\']);
        });
    }
};
');
