DROP TABLE FlightAttendant;
DROP TABLE PlannedFlight;
DROP TABLE Claim;
DROP TABLE CompanyComment;
DROP TABLE CompensationCompany;
DROP TABLE AirlineComment;
DROP TABLE FlightComment;
DROP TABLE Account;
DROP TABLE Flight;
DROP TABLE Airline;
DROP TABLE Airport;

CREATE TABLE Airport (
    id   INTEGER,
    city VARCHAR2(85) NOT NULL
);
ALTER TABLE Airport ADD CONSTRAINT Airport_pk PRIMARY KEY (id);

CREATE TABLE Airline (
    id            INTEGER GENERATED ALWAYS AS IDENTITY START WITH 0 MINVALUE 0, 
    points_per_km INTEGER DEFAULT 0
);
ALTER TABLE Airline ADD CONSTRAINT Airline_pk PRIMARY KEY (id);

CREATE TABLE Flight (
    id                    INTEGER  NOT NULL CHECK (id >= 0), /* ECTRL ID */
    a_dep                 INTEGER  NOT NULL REFERENCES Airport,
    a_des                 INTEGER  NOT NULL REFERENCES Airport,
    filed_off_block_time  TIMESTAMP NOT NULL, 
    actual_off_block_time TIMESTAMP NOT NULL, 
    filed_arrival_time    TIMESTAMP NOT NULL, 
    actual_arrival_time   TIMESTAMP NOT NULL,
    operator_id           INTEGER NOT NULL REFERENCES Airline,
    actual_distance_flown INTEGER NOT NULL CHECK (actual_distance_flown >= 0)
);
ALTER TABLE Flight ADD CONSTRAINT Flight_pk PRIMARY KEY (id);

CREATE TABLE Account (
    username   VARCHAR2(64),
    pword      VARCHAR2(128) NOT NULL,
    first_name VARCHAR2(50)  NOT NULL,
    surname    VARCHAR2(50)  NOT NULL,
    birthdate  DATE          NOT NULL
);
ALTER TABLE Account ADD CONSTRAINT account_pk PRIMARY KEY (username);

CREATE TABLE FlightComment (
    id        INTEGER      GENERATED ALWAYS AS IDENTITY START WITH 0 MINVALUE 0, 
    user_id   VARCHAR2(64) NOT NULL REFERENCES Account,
    post_date TIMESTAMP    NOT NULL,
    stars     INTEGER      CHECK (stars >= 0 AND stars <= 5),
    contents  VARCHAR2(200),
    flight_id INTEGER      NOT NULL REFERENCES Flight
);
ALTER TABLE FlightComment ADD CONSTRAINT FlightComment_pk PRIMARY KEY (id);

CREATE TABLE AirlineComment (
    id         INTEGER      GENERATED ALWAYS AS IDENTITY START WITH 0 MINVALUE 0, 
    user_id    VARCHAR2(64) NOT NULL REFERENCES Account,
    post_date  TIMESTAMP    NOT NULL,
    stars      INTEGER      CHECK (stars >= 0 AND stars <= 5),
    contents   VARCHAR2(200),
    airline_id INTEGER      NOT NULL REFERENCES Airline
);
ALTER TABLE AirlineComment ADD CONSTRAINT AirlineComment_pk PRIMARY KEY (id);

CREATE TABLE CompensationCompany (
    id          INTEGER      GENERATED ALWAYS AS IDENTITY START WITH 0 MINVALUE 0, 
    cname       VARCHAR2(20) NOT NULL,
    success_fee NUMBER       NOT NULL
);
ALTER TABLE CompensationCompany ADD CONSTRAINT CompensationCompany_pk PRIMARY KEY (id);

CREATE TABLE CompanyComment (
    id         INTEGER      GENERATED ALWAYS AS IDENTITY START WITH 0 MINVALUE 0, 
    user_id    VARCHAR2(64) NOT NULL REFERENCES Account,
    post_date  TIMESTAMP    NOT NULL,
    stars      INTEGER      CHECK (stars >= 0 AND stars <= 5),
    contents   VARCHAR2(200),
    company_id INTEGER      NOT NULL REFERENCES CompensationCompany
);
ALTER TABLE CompanyComment ADD CONSTRAINT CompanyComment_pk PRIMARY KEY (id);

CREATE TABLE Claim (
    id         INTEGER      GENERATED ALWAYS AS IDENTITY START WITH 0 MINVALUE 0, 
    user_id    VARCHAR2(64) NOT NULL REFERENCES Account,
    flight_id  INTEGER      NOT NULL REFERENCES Flight,
    post_date  TIMESTAMP    NOT NULL,
    company_id INTEGER      NOT NULL REFERENCES CompensationCompany
);
ALTER TABLE Claim ADD CONSTRAINT Claim_pk PRIMARY KEY (id);

CREATE TABLE PlannedFlight (
    id         INTEGER      GENERATED ALWAYS AS IDENTITY START WITH 0 MINVALUE 0, 
    user_id    VARCHAR2(64) NOT NULL REFERENCES Account,
    dep_date   DATE         NOT NULL,
    arr_date   DATE         NOT NULL,
    dep_aport  INTEGER      NOT NULL REFERENCES Airport,
    arr_aport  INTEGER      NOT NULL REFERENCES Airport
);
ALTER TABLE PlannedFlight ADD CONSTRAINT PlannedFlight_pk PRIMARY KEY (id);

CREATE TABLE FlightAttendant (
    flight_id    INTEGER      NOT NULL REFERENCES Flight,
    attendant_id VARCHAR2(64) NOT NULL REFERENCES Account
);
ALTER TABLE FlightAttendant ADD CONSTRAINT FlightAttendant_pk PRIMARY KEY (flight_id, attendant_id);

