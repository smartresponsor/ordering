<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260422120000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Doctrine-first storage consolidation for outbox idempotency and order items';
    }

    public function up(Schema $schema): void
    {
        if (!$schema->hasTable('order_item')) {
            $table = $schema->createTable('order_item');
            $table->addColumn('id', 'integer', ['autoincrement' => true]);
            $table->addColumn('order_id', 'guid', ['notnull' => false]);
            $table->addColumn('sku', 'string', ['length' => 128]);
            $table->addColumn('quantity', 'integer');
            $table->addColumn('base_price', 'decimal', ['precision' => 12, 'scale' => 2]);
            $table->addColumn('currency', 'string', ['length' => 3]);
            $table->setPrimaryKey(['id']);
            $table->addIndex(['order_id'], 'idx_order_item_order_id');
            $table->addForeignKeyConstraint('orders', ['order_id'], ['id'], ['onDelete' => 'CASCADE']);
        }

        if ($schema->hasTable('idempotency_key')) {
            $table = $schema->getTable('idempotency_key');
            if (!$table->hasColumn('scope')) {
                $table->addColumn('scope', 'string', ['length' => 128, 'notnull' => false]);
            }
            if (!$table->hasColumn('key_hash')) {
                $table->addColumn('key_hash', 'string', ['length' => 128, 'notnull' => false]);
            }
            if (!$table->hasIndex('uniq_idempotency_key_hash')) {
                $table->addUniqueIndex(['key_hash'], 'uniq_idempotency_key_hash');
            }
        }

        if ($schema->hasTable('outbox_messages')) {
            $table = $schema->getTable('outbox_messages');
            if (!$table->hasColumn('attempts')) {
                $table->addColumn('attempts', 'integer', ['default' => 0]);
            }
            if (!$table->hasColumn('available_at')) {
                $table->addColumn('available_at', 'datetime_immutable', ['notnull' => false]);
            }
        }
    }

    public function down(Schema $schema): void
    {
        if ($schema->hasTable('order_item')) {
            $schema->dropTable('order_item');
        }

        if ($schema->hasTable('idempotency_key')) {
            $table = $schema->getTable('idempotency_key');
            if ($table->hasIndex('uniq_idempotency_key_hash')) {
                $table->dropIndex('uniq_idempotency_key_hash');
            }
            if ($table->hasColumn('scope')) {
                $table->dropColumn('scope');
            }
            if ($table->hasColumn('key_hash')) {
                $table->dropColumn('key_hash');
            }
        }

        if ($schema->hasTable('outbox_messages')) {
            $table = $schema->getTable('outbox_messages');
            if ($table->hasColumn('attempts')) {
                $table->dropColumn('attempts');
            }
            if ($table->hasColumn('available_at')) {
                $table->dropColumn('available_at');
            }
        }
    }
}
