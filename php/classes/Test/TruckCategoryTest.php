<?php
namespace Edu\Cnm\FoodTruck\Test;

use Edu\Cnm\FoodTruck\{TruckCategory, Profile, Category, Truck};


// grab the class under scrutiny
require_once(dirname(__DIR__) . "/autoload.php");

// grab the uuid generator
require_once(dirname(__DIR__, 2) . "/lib/uuid.php");
// might not need

/**
 * Full PHPUnit test for the TruckCategory class
 *
 * This is a complete PHPUnit test of the TruckCategory class. It is complete because *ALL* mySQL/PDO enabled methods
 * are tested for both invalid and valid inputs.
 *
 * @see TruckCategory
 * @author Manuel L Escobar III <manuel.esco.20@gmail.com>
 **/
class TruckCategoryTest extends TacoTruckTest {

	/**
	 * profile of the truck with the truckCategory Id
	 * @var $profile
	 */
	protected $profile;

	/**
	 * valid activation to be used
	 * @var $VALID_ACTIVATION_TOKEN
	 */
	protected $VALID_ACTIVATION_TOKEN;

	/**
	 * The valid Hash used
	 * @var $VALID_HASH
	 */
	protected $VALID_HASH;

	/**
	 *truck that has a TruckCategory
	 * @var $truck
	 */
	protected $truck;

	/**
	 * category that has the TruckCategory
	 * @var $category
	 */
	protected $category;

	/**
	 *
	 * create dependent objects before running each test
	 */
	public final function setUp(): void {
		// run the default setUp() method first
		parent::setUp();
		$password = "password";
		$this->VALID_HASH = password_hash($password, PASSWORD_ARGON2I, ["time_cost" => 384]);
		//$this->VALID_ACTIVATION_TOKEN = bin2hex(random_bytes(16));

		// create and insert the mocked profile into mySQL
		$this->profile = new Profile (generateUuidV4(), null ,"abc123@gmail.com", $this->VALID_HASH,  1, "Bimbo", "Baggins", "BimboSwaggins");
		$this->profile->insert($this->getPDO());

		//create and insert the mocked truck
		$this->truck = new Truck(generateUuidV4(), $this->profile->getProfileId(), "We have eggroll.", 1, 35.07720000, 106.66141111, "EggRoll Dynasty", "5058596496", "https://phpunit.de/");
		$this->truck->insert($this->getPDO());

		//create and insert the mocked category
		// todo Marlon suggested using null val for categoryId - change made to all instances of new TruckCategory
		$this->category = new Category(null, "pizza pie");
		$this->category->insert($this->getPDO());
    }

	/**
	 * tests inserting valid truckCategory or... (truck Cat for short Fuzzy!)
	 */
	public function testInsertValidTruckCategory(): void {
		//counts the number of rows and save's it for later
		$numRows = $this->getConnection()->getRowCount("truckCategory");

		// create a new TruckCategory and insert to into mySQL
		$truckCategory = new TruckCategory($this->category->getCategoryId(), $this->truck->getTruckId());

		$truckCategory->insert($this->getPDO());

// grab the data from mySQL and enforce the fields match our expectations
		$pdoTruckCategory = TruckCategory::getTruckCategoryByTruckCategoryCategoryIdAndTruckCategoryTruckId($this->getPDO(), $this->category->getCategoryId(),$this->truck->getTruckId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("truckCategory"));
		$this->assertEquals($pdoTruckCategory->getTruckCategoryCategoryId(), $this->category->getCategoryId());
		$this->assertEquals($pdoTruckCategory->getTruckCategoryTruckId(), $this->truck->getTruckId());
	}

	/**
	 * tests deleting valid TruckCategory
	 */
	public function testDeleteValidTruckCategory(): void {
		//counts the number of rows and save's it for later
		$numRows = $this->getConnection()->getRowCount("truckCategory");

		// create a new TruckCategory and insert to into mySQL
		$truckCategory = new TruckCategory(null, $this->truck->getTruckId());
		$truckCategory->insert($this->getPDO());

		//delete the TruckCategory from mySQL
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("truckCategory"));
		$truckCategory->delete($this->getPDO());

// grab the data from mySQL and enforce the fields match our expectations
		$pdoTruckCategory = TruckCategory::getTruckCategoryByTruckCategoryCategoryIdAndTruckCategoryTruckId($this->getPDO(), $this->category->getCategoryId(), $this->truck->getTruckId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("truckCategory"));
		$this->assertEquals($pdoTruckCategory->getTruckCategoryCategoryId(), $this->category->getCategoryId());
		$this->assertEquals($pdoTruckCategory->getTruckCategoryTruckId(), $this->truck->getTruckId());
	}

	/**
	 * tests getting a valid TruckCategory By TruckCategoryCategoryId And TruckCategoryTruckId
	 * or for short by composite primary
	 */
	public function testGetValidTruckCategoryByTruckCategoryCategoryIdAndTruckCategoryTruckId(): void {
		//counts the number of rows and saves it for later
		$numRows = $this->getConnection()->getRowCount("truckCategory");
		// create a new TruckCategory and insert to into mySQL
		$truckCategory = new TruckCategory($this->category->getCategoryId(), $this->truck->getTruckId());
		$truckCategory->insert($this->getPDO());

// grab the data from mySQL and enforce the fields match our expectations
		$pdoTruckCategory = TruckCategory::getTruckCategoryByTruckCategoryCategoryIdAndTruckCategoryTruckId($this->getPDO(), $truckCategory->getTruckCategoryCategoryId(), $truckCategory->getTruckCategoryTruckId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("truckCategory"));
		$this->assertEquals($pdoTruckCategory->getTruckCategoryCategoryId(), $this->category->getCategoryId());
		$this->assertEquals($pdoTruckCategory->getTruckCategoryTruckId(), $this->truck->getTruckId());
	}
	/**
	 * test grabbing a TruckCategory by content that does not exist
	 **/
	public function testGetInvalidTruckCategoryByTruckCategoryCategoryIdAndTruckCategoryTruckId() : void {
		// grab a TruckCategory by content that does not exist
		$truck = TruckCategory::getTruckCategoryByTruckCategoryCategoryIdAndTruckCategoryTruckId($this->getPDO(), -1, generateUuidV4());
		$this->assertNull($truck);
	}

	/**
	 * tests getting valid truckCategory by the truckCategoryCategoryId
	 */
	public function testGetValidTruckCategoryByTruckCategoryCategoryId(): void {
		//counts the number of rows and saves it for later
		$numRows = $this->getConnection()->getRowCount("truckCategory");

		// create a new TruckCategory and insert to into mySQL
		$truckCategory = new TruckCategory($this->category->getCategoryId(), $this->truck->getTruckId());
		$truckCategory->insert($this->getPDO());

// grab the data from mySQL and enforce the fields match our expectations
		$pdoTruckCategory = TruckCategory::getTruckCategoriesByTruckCategoryCategoryId($this->getPDO(), $truckCategory->getTruckCategoryCategoryId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("truckCategory"));
		$this->assertEquals($pdoTruckCategory[0]->getTruckCategoryCategoryId(), $this->category->getCategoryId());
		$this->assertEquals($pdoTruckCategory[0]->getTruckCategoryTruckId(), $this->truck->getTruckId());
	}

	/**
	 * test grabbing a TruckCategory by content that does not exist
	 **/
	public function testGetInvalidTruckCategoryCategoryId() : void {
		// grab a TruckCategory by content that does not exist
		$truck = TruckCategory::getTruckCategoriesByTruckCategoryCategoryId($this->getPDO(), -1);
		$this->assertEmpty($truck);
	}


	/**
	 * tests getting valid truckCategory by the truckCategoryTruckId
	 */
	public function testGetValidTruckCategoryByTruckCategoryTruckId(): void {
		//counts the number of rows and saves it for later
		$numRows = $this->getConnection()->getRowCount("truckCategory");

		// create a new TruckCategory and insert to into mySQL
		$truckCategory = new TruckCategory($this->category->getCategoryId(), $this->truck->getTruckId());


		$truckCategory->insert($this->getPDO());



// grab the data from mySQL and enforce the fields match our expectations
		$pdoTruckCategory = TruckCategory::getTruckCategoriesByTruckCategoryTruckId($this->getPDO(), $this->truck->getTruckId());

		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("truckCategory"));
		$this->assertEquals($pdoTruckCategory[0]->getTruckCategoryCategoryId(), $this->category->getCategoryId());
		$this->assertEquals($pdoTruckCategory[0]->getTruckCategoryTruckId(), $this->truck->getTruckId());
	}
	/**
	 * test grabbing a TruckCategory by content that does not exist
	 **/
	public function testGetInvalidTruckCategoryTruckId() : void {
		// grab a TruckCategory by content that does not exist
		$truck = TruckCategory::getTruckCategoriesByTruckCategoryTruckId($this->getPDO(), generateUuidV4());
		$this->assertCount(0, $truck);
	}
}



