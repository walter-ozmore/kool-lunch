CREATE TABLE Volunteer (
  volunteerFormID INT PRIMARY KEY AUTO_INCREMENT,
  timeSubmitted INT NOT NULL,
  signupIndividual INT NOT NULL,
  orgID INT DEFAULT NULL,
  weekInTheSummer BIT NOT NULL DEFAULT 0,
  bagDecoration BIT NOT NULL DEFAULT 0,
  fundraising BIT NOT NULL DEFAULT 0,
  supplyGathering BIT NOT NULL DEFAULT 0,
  
  FOREIGN KEY (signupIndividual) REFERENCES Individual(IndividualID),
  FOREIGN KEY (orgID) REFERENCES Organization(orgId)
);