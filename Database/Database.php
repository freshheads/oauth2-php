<?php

/**
 * This file is part of the pantarei/oauth2 package.
 *
 * (c) Wong Hoi Sing Edison <hswong3i@pantarei-design.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pantarei\OAuth2\Database;

use Pantarei\OAuth2\Exception\Exception;

/**
 * Primary front-controller for the database system.
 *
 * This class is uninstantiatable and un-extendable. It acts to encapsulate
 * all control and shepherding of database connections into a single location
 * without the use of globals.
 *
 * @author Wong Hoi Sing Edison <hswong3i@pantarei-design.com>
 */
abstract class Database
{
  /**
   * The stored active connection.
   */
  static protected $connection = NULL;

  /**
   * A processed copy of the database connection information.
   *
   * @var array
   */
  static protected $databaseInfo = NULL;

  /**
   * Gets the connection object for the database.
   */
  final public static function getConnection()
  {
    if (!isset(self::$connection)) {
      // If necessary, a new connection is opened.
      self::$connection = self::openConnection();
    }
    return self::$connection;
  }

  /**
   * Opens a connection to the server.
   */
  final protected static function openConnection()
  {
    if (empty(self::$databaseInfo)) {
      throw new Exception('Need to execute Database::setDatabaseInfo() for database initialization');
    }

    // Prefix Connection class as namespace provided.
    $driver_class = self::$databaseInfo['namespace'] . '\\Connection';

    // Create a new connection and return it.
    $new_connection = new $driver_class(self::$databaseInfo);
    return $new_connection;
  }

  /**
   * Closes a connection to the server.
   */
  final public static function closeConnection()
  {
    self::$connection = NULL;
  }

  /**
   * Setup the database connection information with array.
   *
   * @param array $databaseInfo
   *   An array of options, including:
   *   - namespace: The namespace prefix for Connection class.
   */
  final public static function setDatabaseInfo($databaseInfo = array())
  {
    if (!isset($databaseInfo['namespace'])) {
      throw new Exception('Driver namespace not specificed');
    }
    self::$databaseInfo = $databaseInfo;
  }

  /**
   * Gets the database information array for the database;
   */
  final public static function getDatabaseInfo(){
    return self::$databaseInfo;
  }
  
  public function find($entityName, $id)
  {
    return self::getConnection()->find($entityName, $id);
  }
  
  public function findBy($entityName, $criteria)
  {
    return self::getConnection()->findBy($entityName, $criteria);
  }
  
  public function findOneBy($entityName, $criteria)
  {
    return self::getConnection()->findOneBy($entityName, $criteria);
  }
  
  public function findAll($entityName)
  {
    return self::getConnection()->findAll($entityName);
  }
  
  public function persist($entity)
  {
    return self::getConnection()->persist($entity);
  }
  
  public function remove($entity)
  {
    return self::getConnection()->remove($entity);
  }
}
