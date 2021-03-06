<?php
namespace Edu\Cnm\FoodTruck;
require_once("autoload.php");
require_once(dirname(__DIR__, 2) . "/vendor/autoload.php");

use Ramsey\Uuid\Uuid;

/**
 * Class TruckCategory- Represents the intersection between Trucks and Categories (stores which food categories a particular truck serves)
 * and gets truck category id as well as a truck id
 * @author Manuel Escobar III <mescobar14@cnm.edu>
 *
 **/
class TruckCategory implements \JsonSerializable {
    use ValidateUuid;

    /**
     * id for this truckCategoryCategoryId; this is the primary key
     * @var int $truckCategoryCategoryId
     **/
    private $truckCategoryCategoryId;

    /**
     * id for truckCategoryTruckId; this is the primary key
     * @var Uuid $truckCategoryTruckId
     **/
    private $truckCategoryTruckId;

// Constructor

    /**
     * constructor for this TruckCategory
     *
     * @param int | $newTruckCategoryCategoryId; id of this truck CategoryId
     * @param string|Uuid $newTruckCategoryTruckId; id of this truck category truck Id
     * @throws \InvalidArgumentException if data types are not valid
     * @throws \RangeException if data values are out of bounds (e.g., strings too long, negative integers)
     * @throws \TypeError if data types violate type hints
     * @throws \Exception if some other exception occurs
     * @Documentation https://php.net/manual/en/language.oop5.decon.php
     **/
    public function __construct( $newTruckCategoryCategoryId, $newTruckCategoryTruckId) {
        try {
            $this->setTruckCategoryCategoryId($newTruckCategoryCategoryId);
            $this->setTruckCategoryTruckId($newTruckCategoryTruckId);
        } //determine what exception type was thrown
        catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
            $exceptionType = get_class($exception);
            throw(new $exceptionType($exception->getMessage(), 0, $exception));
        }
    }
    /**
     * accessor method for getting truckCategoryCategoryId
     * @return int value of truckCategoryCategoryId
     *
     */
    public function getTruckCategoryCategoryId(): int {
        return ($this->truckCategoryCategoryId);
    }

    /**
     * mutator method for $truckCategoryCategoryId
     *
     * @param mixed $newTruckCategoryCategoryId
     * @throws \RangeException if $truckCategoryCategoryId is not positive
     * @throws \TypeError if $truckCategoryCategoryId is not a int
     *
     */
    public function setTruckCategoryCategoryId(int $newTruckCategoryCategoryId): void {
        if ($newTruckCategoryCategoryId < 0 || $newTruckCategoryCategoryId >255){
            throw new \RangeException("truckCategoryCategoryId is not between 0 and 255");
        }

        // convert and store the truckCategoryCategoryId
        $this->truckCategoryCategoryId = $newTruckCategoryCategoryId;
    }




    /**
     * accessor method for getting getTruckCategoryTruckId
     * @return Uuid value of getTruckCategoryTruckId
     *
     */
    public function getTruckCategoryTruckId(): Uuid {
        return ($this->truckCategoryTruckId);
    }



    /**
     * mutator method for $truckCategoryCategoryId
     *
     * @param  Uuid | string  $newTruckCategoryTruckId new value of truckCategoryTruckId id
     * @throws \RangeException if $newTruckCategoryTruckId is not positive
     * @throws \TypeError if $newTruckCategoryTruckId is not an integer
     */
    public function setTruckCategoryTruckId($newTruckCategoryTruckId): void {
        try {
            $uuid = self::validateUuid($newTruckCategoryTruckId);
        } catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
            $exceptionType = get_class($exception);
            throw(new $exceptionType($exception->getMessage(), 0, $exception));
        }
        // convert and store the profile id
        $this->truckCategoryTruckId = $uuid;
    }



    /**
     * -- inserts this truckCategory into mySQL
     *
     * -- @param \PDO $pdo PDO connection object
     * -- @throws \PDOException when mySQL related errors occur
     * -- @throws \TypeError if $pdo is not a PDO connection object
     **/
    public function insert(\PDO $pdo): void {

        $query = "INSERT INTO truckCategory(truckCategoryCategoryId, truckCategoryTruckId) VALUES(:truckCategoryCategoryId, :truckCategoryTruckId)";
        $statement = $pdo->prepare($query);
        $parameters = ["truckCategoryCategoryId" => $this->truckCategoryCategoryId, "truckCategoryTruckId" => $this->truckCategoryTruckId->getBytes()];
        $statement->execute($parameters);
    }

    public function delete(\PDO $pdo): void {


        // create query template
        $query = "DELETE FROM truckCategory WHERE truckCategoryCategoryId = :truckCategoryCategoryId AND truckCategoryTruckId = :truckCategoryTruckId";
        $statement = $pdo->prepare($query);

        // bind the member variables to the place holder in the template
        $parameters = ["truckCategoryCategoryId" => $this->truckCategoryCategoryId, "truckCategoryTruckId" => $this->truckCategoryTruckId];
        $statement->execute($parameters);
    }


    /**
     * gets the TruckCategory by TruckCategoryCategoryId And TruckCategoryTruckId
     *
     * @param \PDO $pdo PDO connection object
     * @param  int | null $truckCategoryCategoryId
     * @param  Uuid | string $truckCategoryTruckId
     * @return TruckCategory|null truck found or null if not found
     * @throws \PDOException when mySQL related errors occur
     * @throws \TypeError when a variable are not the correct data type
     **/
    public static function getTruckCategoryByTruckCategoryCategoryIdAndTruckCategoryTruckId(\PDO $pdo, int $truckCategoryCategoryId, $truckCategoryTruckId): ?TruckCategory {

        // create query template
        $query = "SELECT truckCategoryCategoryId, truckCategoryTruckId FROM truckCategory WHERE truckCategoryCategoryId = :truckCategoryCategoryId AND truckCategoryTruckId = :truckCategoryTruckId";
        $statement = $pdo->prepare($query);

        $parameters = ["truckCategoryCategoryId" => $truckCategoryCategoryId, "truckCategoryTruckId" => $truckCategoryTruckId->getBytes()];
        $statement->execute($parameters);

        // grab the truckCategory from mySQL
        try {
            $truckCategory = null;
            $statement->setFetchMode(\PDO::FETCH_ASSOC);
            $row = $statement->fetch();
            if($row !== false) {
                $truckCategory = new truckCategory($row["truckCategoryCategoryId"], $row["truckCategoryTruckId"]);
            }
        } catch(\Exception $exception) {
            // if the row couldn't be converted, rethrow it
            throw(new \PDOException($exception->getMessage(), 0, $exception));
        }
        return ($truckCategory);
    }


    /**
     * gets the TruckCategory by TruckCategoryCategoryId
     *
     * @param \PDO $pdo PDO connection object
     * @param  int | null $truckCategoryCategoryId TruckCategory id to search for
     * @return \SplFixedArray SplFixedArray of truckCategory
     * @throws \PDOException when mySQL related errors occur
     * @throws \TypeError when a variable are not the correct data type
     **/
    public static function getTruckCategoriesByTruckCategoryCategoryId(\PDO $pdo, int $truckCategoryCategoryId): \SPLFixedArray {


		 if(empty($truckCategoryCategoryId) === true){
			 throw(new \PDOException("truck category category ID is invalid"));
		 }

		 // create query template
        $query = "SELECT truckCategoryCategoryId, truckCategoryTruckId FROM truckCategory WHERE truckCategoryCategoryId = :truckCategoryCategoryId";
        $statement = $pdo->prepare($query);

        // bind the truckCategory id to the place holder in the template
        $parameters = ["truckCategoryCategoryId" => $truckCategoryCategoryId];
        $statement->execute($parameters);

		 //build an array on truckCategory
		 $truckCategories = new \SPLFixedArray($statement->rowCount());
		 $statement->setFetchMode(\PDO::FETCH_ASSOC);
		 while(($row = $statement->fetch()) !== false) {
			 try {
				 $truckCategory = new TruckCategory($row["truckCategoryCategoryId"], $row["truckCategoryTruckId"]);
				 $truckCategories[$truckCategories->key()] = $truckCategory;
				 $truckCategories->next();
			 } catch(\Exception $exception) {
				 // if the row couldn't be converted, rethrow it
				 throw(new \PDOException($exception->getMessage(), 0, $exception));
			 }
		 }
		 return ($truckCategories);
    }



    /**
     * gets the TruckCategory by TruckCategoryTruckId
     *
     * @param \PDO $pdo PDO connection object
     * @param  Uuid| string $truckCategoryTruckId TruckCategory id to search for
     * @return \SplFixedArray SplFixedArray of truckCategory
     * @throws \PDOException when mySQL related errors occur
     * @throws \TypeError when a variable are not the correct data type
     **/
    public static function getTruckCategoriesByTruckCategoryTruckId(\PDO $pdo, $truckCategoryTruckId): \SPLFixedArray  {
        // sanitize the truckCategoryId before searching
        try {
            $truckCategoryTruckId = self::validateUuid($truckCategoryTruckId);
        } catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
            throw(new \PDOException($exception->getMessage(), 0, $exception));
        }

        // create query template
        $query = "SELECT truckCategoryCategoryId, truckCategoryTruckId FROM truckCategory WHERE truckCategoryTruckId = :truckCategoryTruckId";
        $statement = $pdo->prepare($query);

        // bind the truckCategory id to the place holder in the template
        $parameters = ["truckCategoryTruckId" => $truckCategoryTruckId->getBytes()];
        $statement->execute($parameters);

        //build an array on truckCategory
        $truckCategories = new \SPLFixedArray($statement->rowCount());
        $statement->setFetchMode(\PDO::FETCH_ASSOC);
        while(($row = $statement->fetch()) !== false) {
            try {
                $truckCategory = new TruckCategory($row["truckCategoryCategoryId"], $row["truckCategoryTruckId"]);
                $truckCategories[$truckCategories->key()] = $truckCategory;
                $truckCategories->next();
            } catch(\Exception $exception) {
                // if the row couldn't be converted, rethrow it
                throw(new \PDOException($exception->getMessage(), 0, $exception));
            }
        }
        return($truckCategories);
    }

    /**
     * stays at bottom of class page
     * formats the state variables for JSON serialization
     * @return array resulting state variables to serialize
     **/
    public function jsonSerialize(): array {
        $fields = get_object_vars($this);
        $fields["truckCategoryCategoryId"] = $this->truckCategoryCategoryId;
        $fields["truckCategoryTruckId"] = $this->truckCategoryTruckId->toString();
        return ($fields);
    }
}