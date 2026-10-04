-- ============================================================
-- EXTENSIONS
-- ============================================================

CREATE EXTENSION IF NOT EXISTS pgcrypto;


-- ============================================================
-- PRODUCT MASTER DATA
-- ============================================================

CREATE TABLE product_categories (
    id              BIGSERIAL PRIMARY KEY,
    parent_id       BIGINT REFERENCES product_categories(id)
                    ON DELETE SET NULL,

    code            VARCHAR(50) NOT NULL UNIQUE,
    name            VARCHAR(150) NOT NULL,

    description     TEXT,

    is_active       BOOLEAN NOT NULL DEFAULT TRUE,

    created_at      TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    updated_at      TIMESTAMPTZ NOT NULL DEFAULT NOW()
);


CREATE TABLE units (
    id              BIGSERIAL PRIMARY KEY,

    code            VARCHAR(20) NOT NULL UNIQUE,
    name            VARCHAR(50) NOT NULL,
    symbol          VARCHAR(20),

    created_at      TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    updated_at      TIMESTAMPTZ NOT NULL DEFAULT NOW()
);


CREATE TABLE products (
    id              BIGSERIAL PRIMARY KEY,

    sku             VARCHAR(100) NOT NULL UNIQUE,
    name            VARCHAR(200) NOT NULL,

    category_id     BIGINT REFERENCES product_categories(id)
                    ON DELETE SET NULL,

    unit_id         BIGINT NOT NULL REFERENCES units(id)
                    ON DELETE RESTRICT,

    barcode         VARCHAR(100),

    description     TEXT,

    is_active       BOOLEAN NOT NULL DEFAULT TRUE,

    created_at      TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    updated_at      TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

CREATE UNIQUE INDEX products_barcode_unique
    ON products(barcode)
    WHERE barcode IS NOT NULL;


-- ============================================================
-- WAREHOUSE MASTER DATA
-- ============================================================

CREATE TABLE warehouses (
    id              BIGSERIAL PRIMARY KEY,

    code            VARCHAR(50) NOT NULL UNIQUE,
    name            VARCHAR(150) NOT NULL,

    address         TEXT,

    is_active       BOOLEAN NOT NULL DEFAULT TRUE,

    created_at      TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    updated_at      TIMESTAMPTZ NOT NULL DEFAULT NOW()
);


-- Physical/storage locations inside a warehouse.
-- You can initially create only one location per warehouse
-- if you don't need detailed bin/rack management yet.

CREATE TABLE warehouse_locations (
    id              BIGSERIAL PRIMARY KEY,

    warehouse_id    BIGINT NOT NULL REFERENCES warehouses(id)
                    ON DELETE CASCADE,

    code            VARCHAR(50) NOT NULL,
    name            VARCHAR(100),

    is_active       BOOLEAN NOT NULL DEFAULT TRUE,

    created_at      TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    updated_at      TIMESTAMPTZ NOT NULL DEFAULT NOW(),

    UNIQUE (warehouse_id, code)
);


-- ============================================================
-- WAREHOUSE DOCUMENTS
-- ============================================================

CREATE TABLE documents (
    id                  BIGSERIAL PRIMARY KEY,

    document_no         VARCHAR(50) NOT NULL UNIQUE,

    /*
        RECEIPT
        ISSUE
        ADJUSTMENT
        CUSTOMER_RETURN
        SUPPLIER_RETURN
        TRANSFER
    */
    document_type       VARCHAR(30) NOT NULL,

    /*
        DRAFT
        POSTED
        CANCELLED
    */
    status              VARCHAR(20) NOT NULL DEFAULT 'DRAFT',

    document_date       DATE NOT NULL DEFAULT CURRENT_DATE,

    /*
        Main warehouse associated with the document.

        For normal receipt/issue/adjustment:
            warehouse_id = affected warehouse

        For transfer:
            warehouse_id can represent the source warehouse.
            from_warehouse_id / to_warehouse_id should be used
            explicitly.
    */
    warehouse_id        BIGINT REFERENCES warehouses(id)
                        ON DELETE RESTRICT,

    from_warehouse_id    BIGINT REFERENCES warehouses(id)
                        ON DELETE RESTRICT,

    to_warehouse_id      BIGINT REFERENCES warehouses(id)
                        ON DELETE RESTRICT,

    /*
        Optional external reference.

        Examples:

        external_system = 'ECOMMERCE'
        external_reference = 'ORDER-12345'

        external_system = 'PURCHASING'
        external_reference = 'PO-2026-0012'
    */
    external_system     VARCHAR(50),
    external_reference  VARCHAR(100),

    /*
        Useful for preventing duplicate API requests.
    */
    idempotency_key     VARCHAR(150),

    notes               TEXT,

    created_by          BIGINT,
    posted_by           BIGINT,

    created_at          TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    updated_at          TIMESTAMPTZ NOT NULL DEFAULT NOW(),

    posted_at           TIMESTAMPTZ,

    cancelled_at        TIMESTAMPTZ
);


CREATE UNIQUE INDEX documents_idempotency_unique
    ON documents(external_system, idempotency_key)
    WHERE external_system IS NOT NULL
      AND idempotency_key IS NOT NULL;


CREATE INDEX documents_type_idx
    ON documents(document_type);

CREATE INDEX documents_status_idx
    ON documents(status);

CREATE INDEX documents_date_idx
    ON documents(document_date);

CREATE INDEX documents_external_reference_idx
    ON documents(external_system, external_reference);


-- ============================================================
-- DOCUMENT ITEMS
-- ============================================================

CREATE TABLE document_items (
    id                  BIGSERIAL PRIMARY KEY,

    document_id         BIGINT NOT NULL REFERENCES documents(id)
                        ON DELETE CASCADE,

    product_id          BIGINT NOT NULL REFERENCES products(id)
                        ON DELETE RESTRICT,

    /*
        Quantity requested/recorded by the document.

        Example:

            Receipt:
                100

            Issue:
                20

            Adjustment:
                3

        Stock direction is determined by document type.
    */
    quantity            NUMERIC(18,3) NOT NULL,

    /*
        Optional snapshot of unit information at transaction time.
        Useful if product master data changes later.
    */
    unit_id             BIGINT NOT NULL REFERENCES units(id)
                        ON DELETE RESTRICT,

    notes               TEXT,

    created_at          TIMESTAMPTZ NOT NULL DEFAULT NOW(),

    CHECK (quantity > 0)
);


CREATE INDEX document_items_document_idx
    ON document_items(document_id);

CREATE INDEX document_items_product_idx
    ON document_items(product_id);


-- ============================================================
-- STOCK MOVEMENT LEDGER
-- ============================================================

CREATE TABLE stock_movements (
    id                  BIGSERIAL PRIMARY KEY,

    document_id         BIGINT NOT NULL REFERENCES documents(id)
                        ON DELETE RESTRICT,

    document_item_id    BIGINT NOT NULL REFERENCES document_items(id)
                        ON DELETE RESTRICT,

    warehouse_id        BIGINT NOT NULL REFERENCES warehouses(id)
                        ON DELETE RESTRICT,

    location_id         BIGINT REFERENCES warehouse_locations(id)
                        ON DELETE RESTRICT,

    product_id          BIGINT NOT NULL REFERENCES products(id)
                        ON DELETE RESTRICT,

    /*
        Positive = stock entering warehouse
        Negative = stock leaving warehouse

        Example:

            Receipt       +100
            Issue          -20
            Adjustment      -5
            Return           +3
    */
    quantity            NUMERIC(18,3) NOT NULL,

    movement_date       TIMESTAMPTZ NOT NULL DEFAULT NOW(),

    created_at          TIMESTAMPTZ NOT NULL DEFAULT NOW()
);


CREATE INDEX stock_movements_product_idx
    ON stock_movements(product_id);

CREATE INDEX stock_movements_warehouse_idx
    ON stock_movements(warehouse_id);

CREATE INDEX stock_movements_location_idx
    ON stock_movements(location_id);

CREATE INDEX stock_movements_date_idx
    ON stock_movements(movement_date);

CREATE INDEX stock_movements_document_idx
    ON stock_movements(document_id);


-- ============================================================
-- CURRENT INVENTORY
-- ============================================================

CREATE TABLE inventory (
    id                  BIGSERIAL PRIMARY KEY,

    warehouse_id        BIGINT NOT NULL REFERENCES warehouses(id)
                        ON DELETE RESTRICT,

    location_id         BIGINT REFERENCES warehouse_locations(id)
                        ON DELETE RESTRICT,

    product_id          BIGINT NOT NULL REFERENCES products(id)
                        ON DELETE RESTRICT,

    /*
        Current physical stock.
    */
    quantity_on_hand    NUMERIC(18,3) NOT NULL DEFAULT 0,

    /*
        Reserved stock can initially remain 0.

        This becomes useful when you integrate with e-commerce.
    */
    quantity_reserved   NUMERIC(18,3) NOT NULL DEFAULT 0,

    created_at          TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    updated_at          TIMESTAMPTZ NOT NULL DEFAULT NOW(),

    UNIQUE (warehouse_id, location_id, product_id),

    CHECK (quantity_on_hand >= 0),
    CHECK (quantity_reserved >= 0),
    CHECK (quantity_reserved <= quantity_on_hand)
);


CREATE INDEX inventory_product_idx
    ON inventory(product_id);

CREATE INDEX inventory_warehouse_idx
    ON inventory(warehouse_id);


-- ============================================================
-- ADJUSTMENT REASONS
-- ============================================================

CREATE TABLE stock_adjustment_reasons (
    id                  BIGSERIAL PRIMARY KEY,

    code                VARCHAR(50) NOT NULL UNIQUE,
    name                VARCHAR(100) NOT NULL,

    /*
        INCREASE
        DECREASE
        BOTH
    */
    direction           VARCHAR(20) NOT NULL DEFAULT 'BOTH',

    is_active           BOOLEAN NOT NULL DEFAULT TRUE,

    created_at          TIMESTAMPTZ NOT NULL DEFAULT NOW()
);


-- ============================================================
-- OPTIONAL: DOCUMENT ATTACHMENTS
-- ============================================================

CREATE TABLE document_attachments (
    id                  BIGSERIAL PRIMARY KEY,

    document_id         BIGINT NOT NULL REFERENCES documents(id)
                        ON DELETE CASCADE,

    file_name           VARCHAR(255) NOT NULL,
    file_path           TEXT NOT NULL,
    mime_type           VARCHAR(100),

    created_at          TIMESTAMPTZ NOT NULL DEFAULT NOW()
);