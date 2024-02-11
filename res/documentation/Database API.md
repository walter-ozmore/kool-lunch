# Functions

## Create functions

### createForm

> [!NOTE]
>
> Used in [file]. [Function # if relevant]

#### Description

This function sorts through the data in `args`, verifying the data exists and is the correct type. It then calls `arrayToInsertString()`, then runs an `INSERT INTO Form` query. Returns either a `1` for failure or the `insert_id` of the new entry.

#### Parameters

| Parameter | Type  | Description                            |
| --------- | ----- | -------------------------------------- |
| args      | array | Contains all values for the form entry |

#### Returns

| Requirement                               | Type | Description                 |
| ----------------------------------------- | ---- | --------------------------- |
| Query returns with an `insert_id`         | INT  | ID for newly inserted entry |
| Query does not return with an `insert_id` | INT  | 1                           |

#### Data verification checks

| Variable                | Is Set | Type Requirement           |
| ----------------------- | ------ | -------------------------- |
| `args["pickupMon"]`     | Y      | 0 or 1                     |
| `args["pickupTue"]`     | Y      | 0 or 1                     |
| `args["pickupWed"]`     | Y      | 0 or 1                     |
| `args["pickupThu"]`     | Y      | 0 or 1                     |
| `args["timeSubmitted"]` | Y      |                            |
| `args["location"]`      | Y      | String                     |
| `args["isEnabled"]`     | Y      | 0 or 1                     |
| `args["lunchesNeeded"]` | Y      | Greater than or equal to 0 |
| `args["allergies"]`     | Y      |                            |

<u>**createFormLink**</u>

<u>**createFormVolunteer**</u>

<u>**createFormVolunteerLink**</u>

<u>**createIndividual**</u>

<u>**createOrg**</u>

<u>**createPickup**</u>

-----

## Get functions


------

## Update functions



----



## Delete functions

# Standards