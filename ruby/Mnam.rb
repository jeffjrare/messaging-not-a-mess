require 'rubygems'
require 'bundler'

class Mnam
  def initialize
    load_configuration
    load_and_connect_to_cass
  end

  def self.init_group group_name, columns
    Mnam.init
  end

  def self.write group_name, key, fields
    Mnam.init
  end

  def self.write_many group_name, columns
  end

  def self.read group_name, key
    Mnam.init
  end


  private

  def self.init
    @mnam_inst = Mnam.new unless @mnam_inst
  end

  def load_configuration
  end

  def load_and_connect_to_cass
  end
end

Mnam.write 'login', 'jeff', [:name => 'jeff']