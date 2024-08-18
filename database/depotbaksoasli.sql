CREATE TABLE admin_depot (
    id_admin    INTEGER NOT NULL AUTO_INCREMENT,
    email_admin VARCHAR(255) NOT NULL,
    password    VARCHAR(255) NOT NULL,
    PRIMARY KEY (id_admin)
);

CREATE TABLE detail_transaksi (
    id_detail_transaksi    INTEGER NOT NULL AUTO_INCREMENT,
    jumlah                 INTEGER NOT NULL,
    total_harga            INTEGER NOT NULL,
    transaksi_id_transaksi INTEGER NOT NULL,
    menu_id_menu           INTEGER NOT NULL,
    PRIMARY KEY (id_detail_transaksi)
);

CREATE TABLE kategori (
    id_kategori   INTEGER NOT NULL AUTO_INCREMENT,
    nama_kategori VARCHAR(255) NOT NULL,
    PRIMARY KEY (id_kategori)
);

CREATE TABLE menu (
    id_menu              INTEGER NOT NULL AUTO_INCREMENT,
    jenis                VARCHAR(255) NOT NULL,
    nama_menu            VARCHAR(255) NOT NULL,
    harga_menu           INTEGER NOT NULL,
    kategori_id_kategori INTEGER NOT NULL,
    PRIMARY KEY (id_menu)
);

CREATE TABLE transaksi (
    id_transaksi      INTEGER NOT NULL AUTO_INCREMENT,
    tanggal_transaksi DATE NOT NULL,
    tipe_order        VARCHAR(255) NOT NULL,
    no_meja           INTEGER,
    jumlah            INTEGER NOT NULL,
    subtotal_harga    INTEGER NOT NULL,
    pajak             INTEGER,
    total_harga       INTEGER NOT NULL,
    status_transaksi  VARCHAR(255) NOT NULL,
    admin_id_admin    INTEGER NOT NULL,
    PRIMARY KEY (id_transaksi)
);

ALTER TABLE detail_transaksi
    ADD CONSTRAINT detail_transaksi_menu_fk FOREIGN KEY (menu_id_menu)
        REFERENCES menu(id_menu);

ALTER TABLE detail_transaksi
    ADD CONSTRAINT detail_transaksi_transaksi_fk FOREIGN KEY (transaksi_id_transaksi)
        REFERENCES transaksi(id_transaksi);

ALTER TABLE menu
    ADD CONSTRAINT menu_kategori_fk FOREIGN KEY (kategori_id_kategori)
        REFERENCES kategori(id_kategori);

ALTER TABLE transaksi
    ADD CONSTRAINT transaksi_admin_fk FOREIGN KEY (admin_id_admin)
        REFERENCES admin_depot(id_admin);

