CREATE TABLE IF NOT EXISTS "receipts" (
    "id" bigint NOT NULL GENERATED ALWAYS AS IDENTITY,
    PRIMARY KEY ("id"),
    "original_filename" character varying(255) NOT NULL,
    "storage_filename" character varying(255) NOT NULL,
    "media_type" character varying(255) NOT NULL,
    "transaction_id" bigint NOT NULL REFERENCES transactions (id) ON DELETE CASCADE,
    "created_at" timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    "updated_at" timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
);