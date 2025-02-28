<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixAimeosInvoiceConstraint extends Migration
{
    public function up()
    {
        Schema::table('mshop_order', function (Blueprint $table) {
            $table->string('invoiceno')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('mshop_order', function (Blueprint $table) {
            $table->string('invoiceno')->nullable(false)->change();
        });
    }
}