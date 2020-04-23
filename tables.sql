CREATE TABLE _users(
    id INT(11) UNSIGNED NOT NULL /*TEST: AUTO_INCREMENT*/,
    nom VARCHAR(200) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY (nom)
);

-- <TEST>
INSERT INTO _users(id, nom) VALUES(1, 'foo');
INSERT INTO _users(id, nom) VALUES(2, 'bar');
-- </TEST>

CREATE TABLE _experiences(
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id INT UNSIGNED NOT NULL REFERENCES _users(id) ON UPDATE CASCADE ON DELETE CASCADE,
    dd_employeur DATE NOT NULL,
    df_employeur DATE NULL,
--     publication VARCHAR(200) NOT NULL,
    poste VARCHAR(200) NOT NULL,
    employeur VARCHAR(200) NOT NULL,
    employeur_ville VARCHAR(200) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE _cv(
    -- TODO: auteur
    -- TODO: date de création + MàJ
    id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    nom VARCHAR(200) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE _cv_experiences(
    experience_id INT UNSIGNED NOT NULL REFERENCES _experiences(id) ON UPDATE CASCADE ON DELETE CASCADE,
    cv_id INT UNSIGNED NOT NULL REFERENCES _cv(id) ON UPDATE CASCADE ON DELETE CASCADE,
    PRIMARY KEY (experience_id, cv_id)
);
