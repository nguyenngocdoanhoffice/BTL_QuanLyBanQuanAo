<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("UPDATE orders SET status = 'preparing' WHERE status = 'pending'");
        DB::statement("UPDATE orders SET status = 'handover' WHERE status = 'confirmed'");
        DB::statement("UPDATE orders SET status = 'in_transit' WHERE status = 'shipped'");
        DB::statement("UPDATE orders SET status = 'completed' WHERE status = 'delivered'");

        DB::statement("ALTER TABLE orders MODIFY status ENUM('preparing','handover','in_transit','completed','cancelled') DEFAULT 'preparing'");
    }

    public function down(): void
    {
        DB::statement("UPDATE orders SET status = 'pending' WHERE status = 'preparing'");
        DB::statement("UPDATE orders SET status = 'confirmed' WHERE status = 'handover'");
        DB::statement("UPDATE orders SET status = 'shipped' WHERE status = 'in_transit'");
        DB::statement("UPDATE orders SET status = 'delivered' WHERE status = 'completed'");

        DB::statement("ALTER TABLE orders MODIFY status ENUM('pending','confirmed','shipped','delivered','cancelled') DEFAULT 'pending'");
    }
};
