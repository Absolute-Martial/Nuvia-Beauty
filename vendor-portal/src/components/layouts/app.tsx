import dynamic from 'next/dynamic';

const VendorLayout = dynamic(() => import('@/components/layouts/admin'));

export default function VendorAppLayout(props: {
  userPermissions: string[];
}) {
  return <VendorLayout {...props} />;
}
