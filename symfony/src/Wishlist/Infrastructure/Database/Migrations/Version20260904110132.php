<?php

declare(strict_types=1);

namespace App\Wishlist\Infrastructure\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260904110132 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add demo wishlists for API review';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            INSERT INTO wishlists (id)
            VALUES
                (1),
                (2),
                (3)
            ON CONFLICT (id) DO NOTHING
        SQL);

        $this->addSql(<<<'SQL'
            SELECT setval(
                pg_get_serial_sequence('wishlists', 'id'),
                GREATEST(
                    (SELECT COALESCE(MAX(id), 0) FROM wishlists),
                    1
                )
            )
        SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            DELETE FROM wishlists
            WHERE id IN (1, 2, 3)
        SQL);
    }
}
