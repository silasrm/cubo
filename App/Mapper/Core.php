<?php

    namespace App\Mapper;
    
    /**
     * @todo adicionar validação de tamanho e tipo do campo
    */
    
    /**
     * minimapper sobre a PDO  para criação de models facilmente e com métodos padrões disponíveis
     * para manipulação dos dados.
     *
     * @author Silas Ribas
    */
    class Core extends \PDO
    {
        protected $adapter  = null;
        protected $table = null;
        protected $fields = array();
        protected $fieldsOptions = array();
        
        public function __construct()
        {
            $this->setAdapter( $this->getDefaultAdapter() );
        }
    
        /**
         * define o adapter padrão
        */
        private function getDefaultAdapter()
        {
            return new \PDO( DATABASE_TYPE . ':host=' . DATABASE_HOST . ';dbname=' . DATABASE_DB, DATABASE_USER, DATABASE_PASS);
        }
        
        /**
         * seta um outro adapter
        */
        public function setAdapter( $adapter )
        {
            $this->adapter = $adapter;
            $this->getColumns();
        }
        
        /**
         * pega as informações da tabela para montar os campos da mesma
        */
        private function describe()
        {
            return $this->adapter
                                    ->query( 'DESCRIBE ' . $this->table )
                                    ->fetchAll( self::FETCH_OBJ ) ;
        }
        
        /**
         * monta o nome das colunas e outras configurações das colunas
        */
        public function getColumns()
        {
            $describe = $this->describe();
            
            foreach( $describe as $column )
            {
                $this->fields[ $column->Field ] = $column->Field;
                $this->fieldsOptions[ $column->Field ] = $column;
            }
        }
        
        /**
         * retorna todos os registros da tabela
        */
        public function findAll()
        {
            return $this->adapter
                                    ->query( 'SELECT * FROM ' . $this->table )
                                    ->fetchAll( self::FETCH_OBJ );
        }
        
        /**
         * retorna o registro com o id informado
        */
        public function find( $id )
        {
            return $this->adapter
                                    ->query( 'SELECT * FROM ' . $this->table . ' WHERE id=' . $id )
                                    ->fetch( self::FETCH_OBJ );
        }
        
        /**
         * retorna o total de registros da tabela
        */
        public function count()
        {
            $result = $this->adapter
                                        ->query( 'SELECT count(id) as total FROM ' . $this->table )
                                        ->fetch( self::FETCH_OBJ );
                                        
            if( $result )
                return $result->total;
            else
                return false;
        }
        
        /**
         * retorna o último id registrado
        */
        public function getLastInsertId()
        {
            return $this->adapter->lastInsertId();
        }
        
        /**
         * gerencia o insert e o update de dados
        */
        public function save( $data )
        {
            // se existe não existe o id, inseri um novo
            if( !array_key_exists( 'id', $data) )
            {
                $this->insert( $data );
                
                return $this->getLastInsertId();
            }
            else
            // se existe id, atualiza
                return $this->update( $data );
        }
        
        /**
         * inseri os dados
        */
        private  function insert( $data )
        {
            // resolve a lista de campos e as chaves para o prepare funcionar usando o array
            $rules = $this->resolveInsertRules( $data );
            
            return $this->adapter
                                    ->prepare( 'INSERT INTO ' . $this->table . ' ( ' . implode(',', $rules['fields'] ) . ' ) VALUES ( ' . implode(',', $rules['columns'] ) . ' )' )
                                    ->execute( $data );
        }
        
        /**
         * resolve a lista de campos e as chaves do insert para o prepare funcionar usando o array
        */
        private  function resolveInsertRules( $data )
        {
            $_listColumnsFields = array();
            $_listColumns= array();
            
            foreach( $data as $column => $value )
            {
                if( array_key_exists( $column, $this->fields )  && $column != 'id' )
                {
                    $_listColumnsFields[] = $this->fields[ $column ];
                    $_listColumns[] = ':' . $this->fields[ $column ];
                }
            }
            
            return array( 'fields' => $_listColumnsFields
                                        ,'columns' => $_listColumns );
        }
        
        /**
         * atualiza os dados do registro
        */
        private  function update( $data )
        {
            $rules = $this->resolveUpdateRules( $data );
            $id = $data['id'];
            unset($data['id']);
            
            return $this->adapter
                                    ->prepare( 'UPDATE ' . $this->table . ' SET ' . implode(',', $rules ) . ' WHERE id=' . $id )
                                    ->execute( $data );
        }
        
        /**
         * resolve a lista de campos e valores do update usando o array
        */
        private function resolveUpdateRules( $data )
        {
            $_listColumnsFields = array();
            
            foreach( $data as $column => $value )
            {
                if( array_key_exists( $column, $this->fields )  && $column != 'id' )
                {
                    $_listColumnsFields[] = $this->fields[ $column ] . '=:' . $this->fields[ $column ];
                }
            }
            
            return $_listColumnsFields;
        }
        
        /**
         * apaga o registro
        */
        public function delete( $id )
        {
            return $this->adapter
                                    ->exec( 'DELETE FROM ' . $this->table . ' WHERE id=' . $id );
        }
        
        /**
         * apaga todos os registros da tabela
        */
        public function deleteAll()
        {
            return $this->adapter
                                    ->exec( 'DELETE FROM ' . $this->table );
        }
    }

?>