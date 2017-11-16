<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Channels Model
 *
 * @property \App\Model\Table\PostsTable|\Cake\ORM\Association\HasMany $Posts
 *
 * @method \App\Model\Entity\Channel get($primaryKey, $options = [])
 * @method \App\Model\Entity\Channel newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Channel[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Channel|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Channel patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Channel[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Channel findOrCreate($search, callable $callback = null, $options = [])
 */
class ChannelsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('channels');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->hasMany('Posts', [
            'foreignKey' => 'channel_id'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->scalar('name')
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->scalar('channel_url')
            ->requirePresence('channel_url', 'create')
            ->notEmpty('channel_url');

        return $validator;
    }

    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->isUnique(['channel_url'], 'The Channel allready exists, please try another one!'));

        return $rules;
    }
}
