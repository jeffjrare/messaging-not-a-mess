require 'rubygems'
require 'bundler'

class MnamCass
  def initialize config_file_path=nil
    connect
  end

  def get_cassandra
    @cassInst
  end


  private

  def prepare

  end

  def connect
    @cassInst = Cassandra.new('mnam', '127.0.0.1:9160')
  end
end