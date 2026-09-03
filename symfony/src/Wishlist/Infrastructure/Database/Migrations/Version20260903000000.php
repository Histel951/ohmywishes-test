<?php

declare(strict_types=1);

namespace App\Wishlist\Infrastructure\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260903000000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create wishlist tables';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE wishlists (
                id SERIAL NOT NULL,
                PRIMARY KEY(id)
            )
        ');

        $this->addSql('
            CREATE TABLE wishlist_items (
                id SERIAL NOT NULL,
                wishlist_id INT NOT NULL,
                product_id INT NOT NULL,
                PRIMARY KEY(id)
            )
        ');

        $this->addSql('
            ALTER TABLE wishlist_items
            ADD CONSTRAINT fk_wishlist_items_wishlist
            FOREIGN KEY (wishlist_id)
            REFERENCES wishlists (id)
            ON DELETE CASCADE
        ');

        $this->addSql('
            ALTER TABLE wishlist_items
            ADD CONSTRAINT uq_wishlist_product
            UNIQUE (wishlist_id, product_id)
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE wishlist_items');
        $this->addSql('DROP TABLE wishlists');
    }
}
