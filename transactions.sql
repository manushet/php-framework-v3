CREATE TABLE IF NOT EXISTS "transactions" (
    "id" bigint NOT NULL GENERATED ALWAYS AS IDENTITY,
    PRIMARY KEY ("id"),
    "description" character varying(255) NOT NULL,
    "amount" numeric NOT NULL,
    "date" timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    "user_id" bigint NOT NULL REFERENCES users (id),
    "created_at" timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    "updated_at" timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
);