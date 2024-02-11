# Return Codes

## API Return Codes

### Success

`100` General success.
`110` Success, everything performed as should be expected.
`120` Success, however results are abnormal (i.e. an update function does not find any matching rows to update).
`130` Success, there was at least one link found (for functions like getAllLinks()).

### Passed data error

`200` General passed data error.
`210` Data is not set as expected.
`220` Data is not the expected type.
`230` Data is not within expected value range.

### Run errors

`300` General run error.
`310` Query failed.

---

# Functions

## Create functions

### createForm

> Used in su.php.

#### Description

This function sorts through the data in `args`, verifying the data exists and is the correct type. It then calls `arrayToInsertString()`, then runs an `INSERT` query.

#### Parameters

| Parameter | Type  | Description                             |
| --------- | ----- | --------------------------------------- |
| args      | array | Contains all values for the form entry. |

#### Returns

| Code | Message         | affectedRows | entryID |
| ---- | --------------- | ------------ | ------- |
| 200  | varies          | N            | N       |
| 310  | Query error     | N            | N       |
| 120  | No inserts made | Y            | N       |
| 110  | Success         | Y            | Y       |

#### Data verification checks

| Variable                | Is Set | Type Requirement           | Required |
| ----------------------- | ------ | -------------------------- | -------- |
| `args["pickupMon"]`     | Y      | 0 or 1                     | Y        |
| `args["pickupTue"]`     | Y      | 0 or 1                     | Y        |
| `args["pickupWed"]`     | Y      | 0 or 1                     | Y        |
| `args["pickupThu"]`     | Y      | 0 or 1                     | Y        |
| `args["timeSubmitted"]` | Y      |                            | N        |
| `args["location"]`      | Y      | String                     | Y        |
| `args["isEnabled"]`     | Y      | 0 or 1                     | Y        |
| `args["lunchesNeeded"]` | Y      | Greater than or equal to 0 | Y        |
| `args["allergies"]`     | Y      |                            | N        |

### createFormLink

> Used in su.php.

#### Description

 Creates a FormLink entry in the database with the given values. It checks for data validity before calling arrayToInsertString and running the query.

#### Parameters

| Parameter | Type  | Description                                                  |
| --------- | ----- | ------------------------------------------------------------ |
| args      | array | Contains all values for the form entry. Must contain individualID and formID. |

#### Returns

| Code | Message         | affectedRows | entryID |
| ---- | --------------- | ------------ | ------- |
| 200  | varies          | N            | N       |
| 310  | Query error     | N            | N       |
| 120  | No inserts made | Y            | N       |
| 110  | Success         | Y            | N       |

#### Data verification checks

| Variable               | Is Set | Type Requirement | Required |
| ---------------------- | ------ | ---------------- | -------- |
| `args["individualID"]` | Y      | INT              | Y        |
| `args["formID"]`       | Y      | INT              | Y        |

### createFormVolunteer

> Used in vol-su.php.

#### Description

 Creates a FormVolunteer entry in the database with the given values. It checks for data validity before calling arrayToInsertString and running the query.

#### Parameters

| Parameter | Type  | Description                                                  |
| --------- | ----- | ------------------------------------------------------------ |
| args      | array | Contains all values for the form entry. Must contain orgID, weekInTheSummer, bagDecoration, fundraising, supplyGathering. |

#### Returns

| Code | Message         | affectedRows | entryID |
| ---- | --------------- | ------------ | ------- |
| 200  | varies          | N            | N       |
| 310  | Query error     | N            | N       |
| 120  | No inserts made | Y            | N       |
| 110  | Success         | Y            | N       |

#### Data verification checks

| Variable                  | Is Set | Type Requirement | Required |
| ------------------------- | ------ | ---------------- | -------- |
| `args["orgID"]`           | Y      | INT              | Y        |
| `args["weekInTheSummer"]` | Y      | 0 or 1           | Y        |
| `args["bagDecoration"]`   | Y      | 0 or 1           | Y        |
| `args["fundraising"]`     | Y      | 0 or 1           | Y        |
| `args["supplyGathering"]` | Y      | 0 or 1           | Y        |
| `args["timeSubmitted"]`   | Y      |                  | N        |

### createFormVolunteerLink

> Used in vol-su.php.

#### Description

 Creates a FormVolunteerLink entry in the database with the given values. It checks for data validity before calling arrayToInsertString and running the query.

#### Parameters

| Parameter | Type  | Description                                                  |
| --------- | ----- | ------------------------------------------------------------ |
| args      | array | Contains all values for the form entry. Must contain individualID and volunteerFormID. |

#### Returns

| Code | Message         | affectedRows | entryID |
| ---- | --------------- | ------------ | ------- |
| 200  | varies          | N            | N       |
| 310  | Query error     | N            | N       |
| 120  | No inserts made | Y            | N       |
| 110  | Success         | Y            | N       |

#### Data verification checks

| Variable                  | Is Set | Type Requirement | Required |
| ------------------------- | ------ | ---------------- | -------- |
| `args["individualID"]`    | Y      | INT              | Y        |
| `args["volunteerFormID"]` | Y      | INT              | Y        |

### createIndividual



### createOrganization

> Used in vol-su.php.

#### Description

 Creates an Organization entry in the database with the given values. It checks for data validity before calling arrayToInsertString and running the query.

#### Parameters

| Parameter | Type  | Description                                                  |
| --------- | ----- | ------------------------------------------------------------ |
| args      | array | Contains all values for the form entry. Must contain orgName and signupContact. |

#### Returns

| Code | Message         | affectedRows | entryID |
| ---- | --------------- | ------------ | ------- |
| 200  | varies          | N            | N       |
| 210  | varies          | N            | N       |
| 310  | Query error     | N            | N       |
| 120  | No inserts made | Y            | N       |
| 110  | Success         | Y            | N       |

#### Data verification checks

| Variable                | Is Set | Type Requirement | Required |
| ----------------------- | ------ | ---------------- | -------- |
| `args["orgName"]`       | Y      |                  | Y        |
| `args["signupContact"]` | Y      | INT              | Y        |
| `args["mainContact"]`   | Y      | INT              | N        |

### createPickup

> Used in su.php.

#### Description

 Creates an Pickup entry in the database with the given values. It checks for data validity before calling arrayToInsertString and running the query.

#### Parameters

| Parameter | Type  | Description                                                  |
| --------- | ----- | ------------------------------------------------------------ |
| args      | array | Contains all values for the form entry. Must contain formID, pickupTime, amount. |

#### Returns

| Code | Message         | affectedRows | entryID |
| ---- | --------------- | ------------ | ------- |
| 200  | varies          | N            | N       |
| 210  | varies          | N            | N       |
| 310  | Query error     | N            | N       |
| 120  | No inserts made | Y            | N       |
| 110  | Success         | Y            | N       |

#### Data verification checks

| Variable             | Is Set | Type Requirement | Required |
| -------------------- | ------ | ---------------- | -------- |
| `args["formID"]`     | Y      | INT              | Y        |
| `args["pickupTime"]` | Y      |                  | Y        |
| `args["amount"]`     | Y      | INT              | N        |

-----

## Get functions

### getAllLinks
#### Description

Get all links for an individual matching the passed individualID. Confirms the ID is numeric.

#### Parameters

| Parameter    | Type | Description             |
| ------------ | ---- | ----------------------- |
| individualID | INT  | ID of target individual |

#### Returns

| Code | Message              | numRows | data |
| ---- | -------------------- | ------- | ---- |
| 220  | Invalid individualID | N       | N    |
| 310  | Query error          | N       | N    |
| 120  | No links             | N       | N    |
| 130  | No linked forms      | N       | Y    |
| 110  | Success              | Y       | Y    |

#### Data verification checks

| Variable       | Is Set | Type Requirement | Required |
| -------------- | ------ | ---------------- | -------- |
| `individualID` | Y      | INT              | Y        |

### getDonations

#### Description

Get the most recent donations. Optional limit can be passed, defaults to 8.

#### Parameters

| Parameter    | Type | Description                     |
| ------------ | ---- | ------------------------------- |
| individualID | INT  | ID of target individual         |
| limit        | INT  | Limit for query. Defaults to 8. |

#### Returns

| Code | Message             | numRows | data |
| ---- | ------------------- | ------- | ---- |
| 230  | Invalid limit value | N       | N    |
| 310  | Query error         | N       | N    |
| 120  | No entries found    | Y       | N    |
| 110  | Success             | Y       | Y    |

#### Data verification checks

| Variable | Is Set | Type Requirement | Required |
| -------- | ------ | ---------------- | -------- |
| `limit`  | N      | Greater than 0   | Y        |

### getForm

#### Description

Get the Form entry matching the given ID. Checks whether the ID is numeric.

#### Parameters

| Parameter | Type | Description       |
| --------- | ---- | ----------------- |
| formID    | INT  | ID of target form |

#### Returns

| Code | Message                   | numRows | data |
| ---- | ------------------------- | ------- | ---- |
| 220  | Invalid formID            | N       | N    |
| 310  | Query error               | N       | N    |
| 120  | No matching entries found | Y       | N    |
| 110  | Success                   | Y       | Y    |

#### Data verification checks

| Variable | Is Set | Type Requirement | Required |
| -------- | ------ | ---------------- | -------- |
| `formID` | N      | INT              | Y        |

### getForms

#### Description

Get all Form entries and all Individual entries linked to those Form entries.

#### Returns

| Code | Message          | numRows | data |
| ---- | ---------------- | ------- | ---- |
| 310  | Query error      | N       | N    |
| 120  | No entries found | Y       | N    |
| 110  | Success          | Y       | Y    |

### getIndividuals

#### Description

Get all Individual entries.

#### Returns

| Code | Message          | numRows | data |
| ---- | ---------------- | ------- | ---- |
| 310  | Query error      | N       | N    |
| 120  | No entries found | Y       | N    |
| 110  | Success          | Y       | Y    |

### getLocations

#### Description

Get all distinct locations from Form.

#### Returns

| Code | Message          | numRows | data |
| ---- | ---------------- | ------- | ---- |
| 310  | Query error      | N       | N    |
| 120  | No entries found | Y       | N    |
| 110  | Success          | Y       | Y    |

### getLunchesNeeded

#### Description

Get the lunchesNeed field from a Form entry matching the given ID. Checks whether the ID is numeric.

#### Parameters

| Parameter | Type | Description       |
| --------- | ---- | ----------------- |
| formID    | INT  | ID of target form |

#### Returns

| Code | Message                   | numRows | data |
| ---- | ------------------------- | ------- | ---- |
| 220  | Invalid formID            | N       | N    |
| 310  | Query error               | N       | N    |
| 120  | No matching entries found | Y       | N    |
| 110  | Success                   | Y       | Y    |

#### Data verification checks

| Variable | Is Set | Type Requirement | Required |
| -------- | ------ | ---------------- | -------- |
| `formID` | N      | INT              | Y        |

### getOrganizations

#### Description

Get all Organizations entries.

#### Returns

| Code | Message          | numRows | data |
| ---- | ---------------- | ------- | ---- |
| 310  | Query error      | N       | N    |
| 120  | No entries found | Y       | N    |
| 110  | Success          | Y       | Y    |

### getDayMeals

#### Description

Get all meals for a specific pickup day. 

#### Parameters

| Parameter | Type | Description       |
| --------- | ---- | ----------------- |
| formID    | INT  | ID of target form |

#### Returns

| Code | Message                   | numRows | data |
| ---- | ------------------------- | ------- | ---- |
| 220  | date is not set           | N       | N    |
| 310  | Query error               | N       | N    |
| 120  | No matching entries found | Y       | N    |
| 110  | Success                   | Y       | Y    |

#### Data verification checks

| Variable | Is Set | Type Requirement | Required |
| -------- | ------ | ---------------- | -------- |
| `data`   | Y      |                  | Y        |

### getVolunteer

#### Description

Get all information for a volunteer from FormVolunteer and Individual given a volunteerFormID. Checks whether the ID is numeric.

#### Parameters

| Parameter       | Type | Description            |
| --------------- | ---- | ---------------------- |
| volunteerFormID | INT  | ID of target volunteer |

#### Returns

| Code | Message                   | numRows | data |
| ---- | ------------------------- | ------- | ---- |
| 220  | Invalid volunteerFormID   | N       | N    |
| 310  | Query error               | N       | N    |
| 120  | No matching entries found | Y       | N    |
| 110  | Success                   | Y       | Y    |

#### Data verification checks

| Variable          | Is Set | Type Requirement | Required |
| ----------------- | ------ | ---------------- | -------- |
| `volunteerFormID` | N      | INT              | Y        |

### getVolunteers

#### Description

Get all Volunteer entries and the information in the linked Individual entries.

#### Returns

| Code | Message          | numRows | data |
| ---- | ---------------- | ------- | ---- |
| 310  | Query error      | N       | N    |
| 120  | No entries found | Y       | N    |
| 110  | Success          | Y       | Y    |


------

## Update functions

### updateAllergies

#### Description

Update the allergies field for the Form entry matching the passed ID. Runs data verification before running the query.

#### Parameters

| Parameter | Type  | Description                                   |
| --------- | ----- | --------------------------------------------- |
| args      | Array | An array containing the formID and new value. |

#### Returns

| Code | Message                   | numRows | data |
| ---- | ------------------------- | ------- | ---- |
| 220  | varies                    | N       | N    |
| 310  | Query error               | N       | N    |
| 120  | No matching entries found | Y       | N    |
| 110  | Success                   | Y       | Y    |

#### Data verification checks

| Variable            | Is Set | Type Requirement | Required |
| ------------------- | ------ | ---------------- | -------- |
| `args["formID"]`    | N      | INT              | Y        |
| `args["allergies"]` | N      | String           | Y        |

### updateIsEnabled

#### Description

Update the isEnabled field for the Form entry matching the passed ID. Runs data verification before running the query.

#### Parameters

| Parameter | Type  | Description                                   |
| --------- | ----- | --------------------------------------------- |
| args      | Array | An array containing the formID and new value. |

#### Returns

| Code | Message                   | numRows | data |
| ---- | ------------------------- | ------- | ---- |
| 220  | Invalid formID            | N       | N    |
| 310  | Query error               | N       | N    |
| 120  | No matching entries found | Y       | N    |
| 110  | Success                   | Y       | Y    |

#### Data verification checks

| Variable         | Is Set | Type Requirement | Required |
| ---------------- | ------ | ---------------- | -------- |
| `args["formID"]` | N      | INT              | Y        |

### updateLocation

#### Description

Update the location field for the Form entry matching the passed ID. Runs data verification before running the query.

#### Parameters

| Parameter | Type  | Description                                   |
| --------- | ----- | --------------------------------------------- |
| args      | Array | An array containing the formID and new value. |

#### Returns

| Code | Message                   | numRows | data |
| ---- | ------------------------- | ------- | ---- |
| 220  | varies                    | N       | N    |
| 310  | Query error               | N       | N    |
| 120  | No matching entries found | Y       | N    |
| 110  | Success                   | Y       | Y    |

#### Data verification checks

| Variable           | Is Set | Type Requirement | Required |
| ------------------ | ------ | ---------------- | -------- |
| `args["formID"]`   | N      | INT              | Y        |
| `args["location"]` | N      | String           | Y        |

### updateLunchesNeeded

#### Description

Update the lunchesNeeded field for the Form entry matching the passed ID. Runs data verification before running the query.

#### Parameters

| Parameter | Type  | Description                                   |
| --------- | ----- | --------------------------------------------- |
| args      | Array | An array containing the formID and new value. |

#### Returns

| Code | Message                   | numRows | data |
| ---- | ------------------------- | ------- | ---- |
| 220  | varies                    | N       | N    |
| 310  | Query error               | N       | N    |
| 120  | No matching entries found | Y       | N    |
| 110  | Success                   | Y       | Y    |

#### Data verification checks

| Variable                | Is Set | Type Requirement | Required |
| ----------------------- | ------ | ---------------- | -------- |
| `args["formID"]`        | N      | INT              | Y        |
| `args["lunchesNeeded"]` | N      | Greater than 0   | Y        |

### updatePickupDay

#### Description

Update one of the pickup day fields for the Form entry matching the passed ID. Runs data verification before running the query.

#### Parameters

| Parameter | Type  | Description                                   |
| --------- | ----- | --------------------------------------------- |
| args      | Array | An array containing the formID and new value. |

#### Returns

| Code | Message                   | numRows | data |
| ---- | ------------------------- | ------- | ---- |
| 220  | Invalid formID            | N       | N    |
| 230  | Invalid formID            | N       | N    |
| 310  | Query error               | N       | N    |
| 120  | No matching entries found | Y       | N    |
| 110  | Success                   | Y       | Y    |

#### Data verification checks

| Variable         | Is Set | Type Requirement    | Required |
| ---------------- | ------ | ------------------- | -------- |
| `args["formID"]` | N      | INT, Greater than 1 | Y        |

----



## Delete functions

### deleteForm

#### Description

Delete the Form entry with a matching formID. Runs data verification before running the query.

#### Parameters

| Parameter | Type | Description        |
| --------- | ---- | ------------------ |
| formID    | INT  | ID of target entry |

#### Returns

| Code | Message                   | affectedRows | data |
| ---- | ------------------------- | ------------ | ---- |
| 220  | Invalid formID            | N            | N    |
| 310  | Query error               | N            | N    |
| 120  | No matching entries found | Y            | N    |
| 110  | Success                   | Y            | Y    |

#### Data verification checks

| Variable | Is Set | Type Requirement    | Required |
| -------- | ------ | ------------------- | -------- |
| `formID` | N      | INT, Greater than 1 | Y        |

### deleteFormLink

#### Description

Delete the FormLink entry with a matching formID and individualID. Runs data verification before running the query.

#### Parameters

| Parameter | Type  | Description                                      |
| --------- | ----- | ------------------------------------------------ |
| args      | Array | An array containing the formID and individualID. |

#### Returns

| Code | Message                   | affectedRows | data |
| ---- | ------------------------- | ------------ | ---- |
| 220  | varies                    | N            | N    |
| 310  | Query error               | N            | N    |
| 120  | No matching entries found | Y            | N    |
| 110  | Success                   | Y            | Y    |

#### Data verification checks

| Variable       | Is Set | Type Requirement | Required |
| -------------- | ------ | ---------------- | -------- |
| `formID`       | N      | INT              | Y        |
| `individualID` | N      | INT              | Y        |

### deleteFormVolunteerLink

#### Description

Delete the FormVolunteerLink entry with a matching formID and individualID. Runs data verification before running the query.

#### Parameters

| Parameter | Type  | Description                                               |
| --------- | ----- | --------------------------------------------------------- |
| args      | Array | An array containing the volunteerFormID and individualID. |

#### Returns

| Code | Message                   | affectedRows | data |
| ---- | ------------------------- | ------------ | ---- |
| 220  | varies                    | N            | N    |
| 310  | Query error               | N            | N    |
| 120  | No matching entries found | Y            | N    |
| 110  | Success                   | Y            | Y    |

#### Data verification checks

| Variable          | Is Set | Type Requirement | Required |
| ----------------- | ------ | ---------------- | -------- |
| `volunteerFormID` | N      | INT              | Y        |
| `individualID`    | N      | INT              | Y        |

### deleteIndividual

#### Description

Delete the Individual entry with a matching individualID. Runs data verification before running the query.

#### Parameters

| Parameter    | Type | Description                 |
| ------------ | ---- | --------------------------- |
| individualID | INT  | The ID of the target entry. |

#### Returns

| Code | Message                   | affectedRows | data |
| ---- | ------------------------- | ------------ | ---- |
| 220  | Invalid individualID      | N            | N    |
| 310  | Query error               | N            | N    |
| 120  | No matching entries found | Y            | N    |
| 110  | Success                   | Y            | Y    |

#### Data verification checks

| Variable       | Is Set | Type Requirement | Required |
| -------------- | ------ | ---------------- | -------- |
| `individualID` | N      | INT              | Y        |

### deleteOrganization

#### Description

Delete the Organization entry with a matching orgID. Runs data verification before running the query.

#### Parameters

| Parameter | Type | Description                 |
| --------- | ---- | --------------------------- |
| orgID     | INT  | The ID of the target entry. |

#### Returns

| Code | Message                   | affectedRows | data |
| ---- | ------------------------- | ------------ | ---- |
| 220  | Invalid orgID             | N            | N    |
| 310  | Query error               | N            | N    |
| 120  | No matching entries found | Y            | N    |
| 110  | Success                   | Y            | Y    |

#### Data verification checks

| Variable | Is Set | Type Requirement | Required |
| -------- | ------ | ---------------- | -------- |
| `orgID`  | N      | INT              | Y        |

### deletePickup

#### Description

Delete the Pickup entry with a matching pickupID. Runs data verification before running the query.

#### Parameters

| Parameter | Type | Description                 |
| --------- | ---- | --------------------------- |
| pickupID  | INT  | The ID of the target entry. |

#### Returns

| Code | Message                   | affectedRows | data |
| ---- | ------------------------- | ------------ | ---- |
| 220  | Invalid pickupID          | N            | N    |
| 310  | Query error               | N            | N    |
| 120  | No matching entries found | Y            | N    |
| 110  | Success                   | Y            | Y    |

#### Data verification checks

| Variable   | Is Set | Type Requirement | Required |
| ---------- | ------ | ---------------- | -------- |
| `pickupID` | N      | INT              | Y        |
