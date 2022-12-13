# Hyperf-policy component

Uses Symfony-like voters to check permissions

Install:
```shell script
composer require onix-systems-php/hyperf-policy
```

To make it works, you need:
1. create policy class based on `OnixSystemsPHP\HyperfPolicy\Policy\AbstractPolicy`
2. add `OnixSystemsPHP\HyperfPolicy\Annotation\Policy` annotation to the policy and setup it's priority (higher number executes first)
3. specify what classes\objects and actions your policy will work with
4. specify vote logic and return one of the values:
   1. `PolicyVote::ACCESS_DENIED` - stop execution and throw exception specified in `getException` method
   2. `PolicyVote::ACCESS_GRANTED` - allow current action and skip all following policies
   3. `PolicyVote::ACCESS_ABSTAIN` - go to next policy, allow action if no policies left to check
