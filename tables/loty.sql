DROP TABLE FlightAttendant CASCADE CONSTRAINTS;
DROP TABLE PlannedFlight CASCADE CONSTRAINTS;
DROP TABLE Claim CASCADE CONSTRAINTS;
DROP TABLE CompanyComment CASCADE CONSTRAINTS;
DROP TABLE CompensationCompany CASCADE CONSTRAINTS;
DROP TABLE AirlineComment CASCADE CONSTRAINTS;
DROP TABLE FlightComment CASCADE CONSTRAINTS;
DROP TABLE Account CASCADE CONSTRAINTS;
DROP TABLE Flight CASCADE CONSTRAINTS;
DROP TABLE Airline CASCADE CONSTRAINTS;
DROP TABLE Airport CASCADE CONSTRAINTS;

CREATE TABLE Airport (
    id              VARCHAR2(4)  PRIMARY KEY, /* ICAO airport code */
    airport_name    VARCHAR2(85) NOT NULL,
    city            VARCHAR2(85) NOT NULL,
    country         VARCHAR2(85) NOT NULL,
    latitude        DECIMAL(8,6) NOT NULL,
    longitude       DECIMAL(9,6) NOT NULL   
);

CREATE TABLE Airline (
    id              VARCHAR2(3) PRIMARY KEY, /* ICAO airline designator */
    airline_name    VARCHAR2(85) NOT NULL, 
    country         VARCHAR2(85) NOT NULL,
    points_per_km   INTEGER DEFAULT NULL
);

CREATE TABLE Flight (
    id /* ECTRL ID */     INTEGER  NOT NULL CHECK (id >= 0) PRIMARY KEY,
    aof_dep               VARCHAR2(4) NOT NULL REFERENCES Airport,
    aof_des               VARCHAR2(4) NOT NULL REFERENCES Airport,
    filed_off_block_time  TIMESTAMP NOT NULL, 
    actual_off_block_time TIMESTAMP NOT NULL, 
    filed_arrival_time    TIMESTAMP NOT NULL, 
    actual_arrival_time   TIMESTAMP NOT NULL,
    operator_id           VARCHAR2(3) NOT NULL REFERENCES Airline,
    actual_distance_flown INTEGER NOT NULL CHECK (actual_distance_flown >= 0)
    /* nautical miles */
);

CREATE TABLE Account (
    username   VARCHAR2(64)  PRIMARY KEY,
    pword      VARCHAR2(128) NOT NULL,
    first_name VARCHAR2(50)  NOT NULL,
    surname    VARCHAR2(50)  NOT NULL,
    birthdate  DATE          NOT NULL
);

CREATE TABLE FlightComment (
    id        INTEGER      GENERATED ALWAYS AS IDENTITY START WITH 0 MINVALUE 0
                           PRIMARY KEY, 
    user_id   VARCHAR2(64) NOT NULL REFERENCES Account,
    post_date TIMESTAMP    NOT NULL,
    stars     INTEGER      CHECK (stars >= 0 AND stars <= 5),
    contents  VARCHAR2(200),
    flight_id INTEGER      NOT NULL REFERENCES Flight
);

CREATE TABLE AirlineComment (
    id         INTEGER      GENERATED ALWAYS AS IDENTITY START WITH 0 MINVALUE 0
                            PRIMARY KEY, 
    user_id    VARCHAR2(64) NOT NULL REFERENCES Account,
    post_date  TIMESTAMP    NOT NULL,
    stars      INTEGER      CHECK (stars >= 0 AND stars <= 5),
    contents   VARCHAR2(200),
    airline_id VARCHAR2(3)  NOT NULL REFERENCES Airline
);

CREATE TABLE CompensationCompany (
    id          INTEGER      GENERATED ALWAYS AS IDENTITY START WITH 0 MINVALUE 0
                             PRIMARY KEY, 
    cname       VARCHAR2(20) NOT NULL,
    success_fee NUMBER       NOT NULL
);

CREATE TABLE CompanyComment (
    id         INTEGER      GENERATED ALWAYS AS IDENTITY START WITH 0 MINVALUE 0
                            PRIMARY KEY, 
    user_id    VARCHAR2(64) NOT NULL REFERENCES Account,
    post_date  TIMESTAMP    NOT NULL,
    stars      INTEGER      CHECK (stars >= 0 AND stars <= 5),
    contents   VARCHAR2(200),
    company_id INTEGER      NOT NULL REFERENCES CompensationCompany
);

CREATE TABLE Claim (
    id         INTEGER      GENERATED ALWAYS AS IDENTITY START WITH 0 MINVALUE 0
                            PRIMARY KEY, 
    user_id    VARCHAR2(64) NOT NULL REFERENCES Account,
    flight_id  INTEGER      NOT NULL REFERENCES Flight,
    post_date  TIMESTAMP    NOT NULL,
    company_id INTEGER      NOT NULL REFERENCES CompensationCompany
);

CREATE TABLE PlannedFlight (
    id         INTEGER      GENERATED ALWAYS AS IDENTITY START WITH 0 MINVALUE 0
                            PRIMARY KEY, 
    user_id    VARCHAR2(64) NOT NULL REFERENCES Account,
    dep_date   DATE         NOT NULL,
    arr_date   DATE         NOT NULL,
    dep_aport  VARCHAR2(4)  NOT NULL REFERENCES Airport,
    arr_aport  VARCHAR2(4)  NOT NULL REFERENCES Airport
);

CREATE TABLE FlightAttendant (
    flight_id    INTEGER      NOT NULL REFERENCES Flight,
    attendant_id VARCHAR2(64) NOT NULL REFERENCES Account,
    PRIMARY KEY (flight_id, attendant_id)
);
