# Player Tags

Show a tag below the player's name tag!

### Poggit:

Download count: [![](https://poggit.pmmp.io/shield.dl.total/PlayerTags)](https://poggit.pmmp.io/p/PlayerTags)
Download link: [![](https://poggit.pmmp.io/shield.state/PlayerTags)](https://poggit.pmmp.io/p/PlayerTags)

## Installation

* Download the latest release from the Poggit above or from [releases](https://github.com/sylvrs/PlayerTags/releases)!
* Install the plugin in your server's 'plugins' folder.
* Restart your server.
* Enjoy!

## Wiki

To see examples or API, please visit the [wiki](https://github.com/sylvrs/PlayerTags/wiki).

## Supported Tags:

### Formatting

|      Name     | Description                                                                      |
|:-------------:|----------------------------------------------------------------------------------|
|     {line}    | Creates another line.                                                            |

### Default

|      Name     | Description                                               |
|:-------------:|-----------------------------------------------------------|
|  {x}/{y}/{z}  | The player's coordinates                                  |
|    {level}    | The player's current level                                |
|   {item_id}   | The ID of the item in the player's hand                   |
| {item_damage} | The damage of the item in the player's hand               |
|  {item_count} | The amount of the item in the player's hand               |
|  {item_name}  | The name of the item in the player's hand                 |
|      {ip}     | The player's IP address                                   |
|   {gamemode}  | The player's gamemode                                     |
|     {ping}    | The player's current ping                                 |
|     {cps}     | The player's current CPS                                  |
|    {health}   | The player's current health                               |
|  {max_health} | The player's maximum health                               |
|  {health_bar} | The player's health converted into a bar                  |
|    {device}   | The player's current device                               |
|  {input_mode} | The player's current input mode (Touch, Controller, KB+M) |
|      {os}     | The player's current OS                                   |

### AdvancedJobs

|        Name       | Description                                                                     |
|:-----------------:|---------------------------------------------------------------------------------|
|       {job}       | The player's job ('Unemployed', if the player doesn't have one)                 |
| {job_information} | The information on the player's job ('', if the player doesn't have one)        |
|   {job_progress}  | The current progress on the player's job ('-1', if the player doesn't have one) |
### CombatLogger

|        Name       | Description                                                                     |
|:-----------------:|---------------------------------------------------------------------------------|
|      {timer}      | The player's current timer on the plugin ('', if the player doesn't have one)   |

### EconomyAPI

|      Name      | Description                                                                     |
|:--------------:|---------------------------------------------------------------------------------|
|     {money}    | Show's the player's money                                                       |
| {money_prefix} | Retrieves the monetary unit for EconomyAPI                                      |

### FactionsPro

|       Name      | Description                                                                     |
|:---------------:|---------------------------------------------------------------------------------|
|  {faction_name} | The name of the player's current faction ('None', if not in one)                |
| {faction_power} | The faction's current power level ('', if not in one)                           |

### KDR

|   Name   | Description                               |
|:--------:|-------------------------------------------|
|  {kills} | The number of kill points for the player  |
| {deaths} | The number of death points for the player |
|   {kdr}  | The kill/death ratio for the player       |

### PiggyFactions

|       Name      | Description                                                      |
|:---------------:|------------------------------------------------------------------|
|  {faction_name} | The name of the player's current faction ('None', if not in one) |
| {faction_power} | The faction's current power level ('', if not in one)            |
|  {faction_rank} | The player's rank in PiggyFactions. ('', if none)                |

### PurePerms

|   Name   | Description                                       |
|:--------:|---------------------------------------------------|
|  {rank}  | The player's rank. ('N/A', if a rank isn't found) |
| {prefix} | The player's prefix                               |
| {suffix} | The player's suffix                               |

### RankUp

|   Name   | Description                                               |
|:--------:|-----------------------------------------------------------|
| {rankup} | The player's current rank. ('N/A', if a rank isn't found) |

### RedSkyblock

|      Name      | Description                                                                 |
|:--------------:|-----------------------------------------------------------------------------|
|  {island_name} | The name of the player's island ('', if the player doesn't have an island)  |
|  {island_rank} | The player's rank                                                           |
| {island_value} | The value of the player's island ('', if the player doesn't have an island) |

### SkyBlock

|        Name       | Description                                                                      |
|:-----------------:|----------------------------------------------------------------------------------|
| {island_category} | The category of the player's island ('N/A', if the player doesn't have one)      |
|   {island_rank}   | The rank of the player on the island ('Unknown', if the player doesn't have one) |
|   {island_type}   | The player's island type ('N/A', if the player doesn't have one)                 |
