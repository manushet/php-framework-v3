CREATE TABLE IF NOT EXISTS "users" (
  "id" bigint NOT NULL GENERATED ALWAYS AS IDENTITY,
  PRIMARY KEY ("id"),
  "email" character varying(80) NOT NULL,
  UNIQUE ("email"),
  "password" character varying(255) NOT NULL,
  "age" smallint NOT NULL,
  "country" character varying(50) NOT NULL,
  "social_media_url" character varying(255) NOT NULL,
  "created_at" timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  "updated_at" timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
);