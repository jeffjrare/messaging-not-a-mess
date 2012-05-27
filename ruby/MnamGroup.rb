require 'rubygems'
require 'bundler'

module MnamGroup
  def self.init_group group_name, columns
    Mnam.init
  end

  def self.write group_name, key, fields
    Mnam.init

    puts group_name
  end

  def self.write_many group_name, columns
    columns.each{ |key, fields| write group_name, key, fields }
  end

  def self.read group_name, key
    Mnam.init
  end


  private

  def self.init

  end
end

Mnam.write_many 'login', [{'jeff' => [:name => 'jeff']}, {'jeff' => [:name => 'jeff']}, {'jeff' => [:name => 'jeff']}]