<?php
/**
 * File containing the UserHandler interface
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\Core\Persistence\Legacy\User;
use eZ\Publish\SPI\Persistence\User,
    eZ\Publish\SPI\Persistence\User\Handler as BaseUserHandler,
    eZ\Publish\SPI\Persistence\User\Role,
    eZ\Publish\SPI\Persistence\User\RoleUpdateStruct,
    eZ\Publish\SPI\Persistence\User\Policy,
    eZ\Publish\Core\Persistence\Legacy\User\Role\Gateway as RoleGateway,
    eZ\Publish\Core\Base\Exceptions\NotFoundException as NotFound;

/**
 * Storage Engine handler for user module
 */
class Handler implements BaseUserHandler
{
    /**
     * Gaateway for storing user data
     *
     * @var \eZ\Publish\Core\Persistence\Legacy\User\Gateway
     */
    protected $userGateway;

    /**
     * Gaateway for storing role data
     *
     * @var \eZ\Publish\Core\Persistence\Legacy\User\Role\Gateway
     */
    protected $roleGateway;

    /**
     * Mapper for user related objects
     *
     * @var \eZ\Publish\Core\Persistence\Legacy\User\Mapper
     */
    protected $mapper;

    /**
     * Construct from userGateway
     *
     * @param \eZ\Publish\Core\Persistence\Legacy\User\Gateway $userGateway
     * @param \eZ\Publish\Core\Persistence\Legacy\User\Role\Gateway $roleGateway
     * @return void
     */
    public function __construct( Gateway $userGateway, RoleGateway $roleGateway, Mapper $mapper )
    {
        $this->userGateway = $userGateway;
        $this->roleGateway = $roleGateway;
        $this->mapper = $mapper;
    }

    /**
     * Create a user
     *
     * The User struct used to create the user will contain an ID which is used
     * to reference the user.
     *
     * @param \eZ\Publish\SPI\Persistence\User $user
     * @return \eZ\Publish\SPI\Persistence\User
     */
    public function create( User $user )
    {
        $this->userGateway->createUser( $user );
        return $user;
    }

    /**
     * Load user with user ID.
     *
     * @param mixed $userId
     * @return \eZ\Publish\SPI\Persistence\User
     */
    public function load( $userId )
    {
        $data = $this->userGateway->load( $userId );

        if ( empty( $data ) )
        {
            throw new NotFound( 'user', $userId );
        }

        return $this->mapper->mapUser( reset( $data ) );
    }

    /**
     * Load user with user login / email.
     *
     * @param string $login
     * @param boolean $alsoMatchEmail Also match user email, caller must verify that $login is a valid email address.
     * @return \eZ\Publish\SPI\Persistence\User[]
     */
    public function loadByLogin( $login, $alsoMatchEmail = false )
    {
        $data = $this->userGateway->loadByLoginOrMail( $login, $alsoMatchEmail ? $login : null );

        if ( empty( $data ) )
        {
            throw new NotFound( 'user', $login );
        }

        return $this->mapper->mapUsers( $data );
    }

    /**
     * Update the user information specified by the user struct
     *
     * @param \eZ\Publish\SPI\Persistence\User $user
     */
    public function update( User $user )
    {
        $this->userGateway->updateUser( $user );
    }

    /**
     * Delete user with the given ID.
     *
     * @param mixed $userId
     */
    public function delete( $userId )
    {
        $this->userGateway->deleteUser( $userId );
    }

    /**
     * Create new role
     *
     * @param \eZ\Publish\SPI\Persistence\User\Role $role
     * @return \eZ\Publish\SPI\Persistence\User\Role
     */
    public function createRole( Role $role )
    {
        $this->roleGateway->createRole( $role );

        foreach ( $role->policies as $policy )
        {
            $this->addPolicy( $role->id, $policy );
        }

        return $role;
    }

    /**
     * Load a specified role by $roleId
     *
     * @param mixed $roleId
     * @return \eZ\Publish\SPI\Persistence\User\Role
     * @throws \eZ\Publish\API\Repository\Exceptions\NotFoundException If role is not found
     */
    public function loadRole( $roleId )
    {
        $data = $this->roleGateway->loadRole( $roleId );

        if ( empty( $data ) )
        {
            throw new NotFound( 'role', $roleId );
        }

        return $this->mapper->mapRole( $data );
    }

    /**
     * Load a specified role by $identifier
     *
     * @param string $identifier
     * @return \eZ\Publish\SPI\Persistence\User\Role
     * @throws \eZ\Publish\API\Repository\Exceptions\NotFoundException If role is not found
     */
    public function loadRoleByIdentifier( $identifier )
    {
        $data = $this->roleGateway->loadRoleByIdentifier( $identifier );

        if ( empty( $data ) )
        {
            throw new NotFound( 'role', $identifier );
        }

        return $this->mapper->mapRole( $data );
    }

    /**
     * Load all roles
     *
     * @return \eZ\Publish\SPI\Persistence\User\Role[]
     */
    public function loadRoles()
    {
        $data = $this->roleGateway->loadRoles();

        return $this->mapper->mapRoles( $data );
    }

    /**
     * Load roles assigned to a user/group (not including inherited roles)
     *
     * @param mixed $groupId
     * @return \eZ\Publish\SPI\Persistence\User\Role[]
     */
    public function loadRolesByGroupId( $groupId )
    {
        $data = $this->roleGateway->loadRolesForContentObjects( array( $groupId ) );

        return $this->mapper->mapRoles( $data );
    }

    /**
     * Update role
     *
     * @param \eZ\Publish\SPI\Persistence\User\RoleUpdateStruct $role
     */
    public function updateRole( RoleUpdateStruct $role )
    {
        $this->roleGateway->updateRole( $role );
    }

    /**
     * Delete the specified role
     *
     * @param mixed $roleId
     */
    public function deleteRole( $roleId )
    {
        $this->roleGateway->deleteRole( $roleId );
    }

    /**
     * Adds a policy to a role
     *
     * @param mixed $roleId
     * @param \eZ\Publish\SPI\Persistence\User\Policy $policy
     * @return \eZ\Publish\SPI\Persistence\User\Policy
     */
    public function addPolicy( $roleId, Policy $policy )
    {
        $this->roleGateway->addPolicy( $roleId, $policy );

        return $policy;
    }

    /**
     * Update a policy
     *
     * Replaces limitations values with new values.
     *
     * @param \eZ\Publish\SPI\Persistence\User\Policy $policy
     */
    public function updatePolicy( Policy $policy )
    {
        $this->roleGateway->removePolicyLimitations( $policy->id );
        $this->roleGateway->addPolicyLimitations( $policy->id, $policy->limitations );
    }

    /**
     * Removes a policy from a role
     *
     * @param mixed $roleId
     * @param mixed $policyId
     * @return void
     */
    public function removePolicy( $roleId, $policyId )
    {
        // Each policy can only be associated to exactly one role. Thus it is
        // sufficient to use the policyId for identification and just remove
        // the policiy completely.
        $this->roleGateway->removePolicy( $policyId );
    }

    /**
     * Returns the user policies associated with the user (including inherited policies from user groups)
     *
     * @param mixed $userId
     * @return \eZ\Publish\SPI\Persistence\User\Policy[]
     */
    public function loadPoliciesByUserId( $userId )
    {
        $data = $this->roleGateway->loadPoliciesByUserId( $userId );

        return $this->mapper->mapPolicies( $data );
    }

    /**
     * Assign role to user group with given limitation
     *
     * The limitation array may look like:
     * <code>
     *  array(
     *      'Subtree' => array(
     *          '/1/2/',
     *          '/1/4/',
     *      ),
     *      'Foo' => array( 'Bar' ),
     *      …
     *  )
     * </code>
     *
     * Where the keys are the limitation identifiers, and the respective values
     * are an array of limitation values. The limitation parameter is optional.
     *
     * @param mixed $groupId
     * @param mixed $roleId
     * @param array $limitation
     */
    public function assignRole( $groupId, $roleId, array $limitation = null )
    {
        $limitation = $limitation ?: array( '' => array( '' ) );
        $this->userGateway->assignRole( $groupId, $roleId, $limitation );
    }

    /**
     * Un-assign a role
     *
     * @param mixed $groupId The group / user Id to un-assign a role from
     * @param mixed $roleId
     */
    public function unAssignRole( $groupId, $roleId )
    {
        $this->userGateway->removeRole( $groupId, $roleId );
    }
}
