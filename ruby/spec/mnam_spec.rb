require 'rubygems'
require 'spec_helper'

describe Mnam do
  before :each do 
    cass_inst = mock('Cassandra')
    cass_inst.stub(:insert).and_return(true) 

    mnam_cass = mock('MnamCass')
    mnam_cass.stub(:new).and_return(mnam_cass)
    mnam_cass.stub(:get_cassandra).and_return(cass_inst)

    Mnam.force_mnam_cass(mnam_cass)
  end

  it 'should write single message' do 
    Mnam.write('login', 'jeff', {:name => 'jeff'}).should be_true
  end

  it 'should write many messages' do
    Mnam.write_many('login', [{'jeff' => [:name => 'jeff']}, {'jeff' => [:name => 'jeff']}, {'jeff' => [:name => 'jeff']}]).should be_true
  end

  it 'should read single message'
end