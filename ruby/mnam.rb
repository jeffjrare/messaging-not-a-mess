require 'rubygems'
require 'bundler'
require 'cassandra'
require_relative 'mnam_cass'

module Mnam
  def self.force_mnam_cass val
    @mnam_cass = val
  end

  def self.write group_name, key, fields
    Mnam.init
    @mnam_cass.get_cassandra.insert(:login, "5", {'screen_name' => "buttonscat"})
  end

  def self.write_many group_name, columns
    columns.each{ |key, fields| write group_name, key, fields }
  end

  def self.read group_name, key
    Mnam.init
  end


  private

  def self.init
    @mnam_cass = MnamCass.new unless @mnam_cass
  end
end

Mnam.write_many 'login', [{'jeff' => [:name => 'jeff']}, {'jeff' => [:name => 'jeff']}, {'jeff' => [:name => 'jeff']}]