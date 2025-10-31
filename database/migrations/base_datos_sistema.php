<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tabla de Usuarios (Conductores)
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('first_name', 50);
            $table->string('last_name', 50);
            $table->string('email', 100)->unique();
            $table->string('plate_number', 20)->unique()->comment('Placa del vehículo - usado para login');
            $table->string('password');
            $table->string('phone', 20)->nullable();
            $table->string('vehicle_brand', 50)->nullable();
            $table->string('vehicle_model', 50)->nullable();
            $table->year('vehicle_year')->nullable();
            $table->integer('points')->default(0)->comment('Puntos acumulados del usuario');
            $table->boolean('active')->default(true);
            $table->timestamp('last_login')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Tabla de Administradores
        Schema::create('administrators', function (Blueprint $table) {
            $table->id('admin_id');
            $table->string('first_name', 50);
            $table->string('last_name', 50);
            $table->string('email', 100)->unique();
            $table->string('username', 50)->unique();
            $table->string('password');
            $table->string('phone', 20)->nullable();
            $table->string('picture')->nullable()->comment('Ruta de la foto de perfil');
            $table->enum('role', ['super_admin', 'admin', 'moderator'])->default('admin');
            $table->boolean('active')->default(true);
            $table->timestamp('last_login')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Tabla de Recompensas (Catálogo)
        Schema::create('rewards', function (Blueprint $table) {
            $table->id('reward_id');
            $table->string('title', 100);
            $table->text('description');
            $table->integer('points_required')->comment('Puntos necesarios para canjear');
            $table->string('image')->nullable()->comment('Imagen de la recompensa');
            $table->string('category', 50)->nullable()->comment('Categoría: descuentos, productos, servicios, etc.');
            $table->integer('stock')->default(0)->comment('Cantidad disponible');
            $table->boolean('active')->default(true);
            $table->date('expiration_date')->nullable()->comment('Fecha de vencimiento de la oferta');
            $table->text('terms_conditions')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Tabla de Transacciones de Puntos
        Schema::create('point_transactions', function (Blueprint $table) {
            $table->id('transaction_id');
            $table->foreignId('user_id')->constrained('users', 'user_id')->onDelete('cascade');
            $table->enum('type', ['earned', 'redeemed', 'expired', 'adjusted'])->comment('Tipo de transacción');
            $table->integer('points')->comment('Cantidad de puntos (positivo o negativo)');
            $table->string('description')->comment('Descripción de la transacción');
            $table->foreignId('reward_id')->nullable()->constrained('rewards', 'reward_id')->onDelete('set null');
            $table->foreignId('admin_id')->nullable()->constrained('administrators', 'admin_id')->onDelete('set null');
            $table->timestamps();
        });

        // Tabla de Canjes de Recompensas
        Schema::create('reward_redemptions', function (Blueprint $table) {
            $table->id('redemption_id');
            $table->foreignId('user_id')->constrained('users', 'user_id')->onDelete('cascade');
            $table->foreignId('reward_id')->constrained('rewards', 'reward_id')->onDelete('cascade');
            $table->integer('points_used')->comment('Puntos utilizados en el canje');
            $table->enum('status', ['pending', 'approved', 'delivered', 'cancelled'])->default('pending');
            $table->string('redemption_code', 20)->unique()->comment('Código único de canje');
            $table->text('notes')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('administrators', 'admin_id')->onDelete('set null');
            $table->timestamps();
        });

        // Tabla de Actividades (Para registrar acciones que otorgan puntos)
        Schema::create('activities', function (Blueprint $table) {
            $table->id('activity_id');
            $table->string('name', 100);
            $table->text('description');
            $table->integer('points_awarded')->comment('Puntos que se otorgan por esta actividad');
            $table->enum('type', ['purchase', 'referral', 'review', 'visit', 'other'])->default('other');
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        // Tabla de Registro de Actividades de Usuarios
        Schema::create('user_activities', function (Blueprint $table) {
            $table->id('user_activity_id');
            $table->foreignId('user_id')->constrained('users', 'user_id')->onDelete('cascade');
            $table->foreignId('activity_id')->constrained('activities', 'activity_id')->onDelete('cascade');
            $table->integer('points_earned')->comment('Puntos ganados en esta actividad');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_activities');
        Schema::dropIfExists('activities');
        Schema::dropIfExists('reward_redemptions');
        Schema::dropIfExists('point_transactions');
        Schema::dropIfExists('rewards');
        Schema::dropIfExists('administrators');
        Schema::dropIfExists('users');
    }
};