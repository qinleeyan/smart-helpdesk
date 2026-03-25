import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, usePage } from '@inertiajs/react';
import { IconPlus } from '@tabler/icons-react';

export default function Index({ articles }) {
    const { flash } = usePage().props;
    return (
        <AuthenticatedLayout header={<h1 className="text-base font-medium">Manage Articles</h1>}>
            <Head title="Manage KB" />
            <div className="flex flex-col gap-4 py-4 md:py-6 px-4 lg:px-6">
                <div className="flex justify-end">
                    <Link href={route('kb.admin.create')} className="inline-flex items-center gap-2 rounded-md bg-primary px-3 py-2 text-sm font-medium text-primary-foreground shadow-sm hover:bg-primary/90"><IconPlus className="size-4" /> New Article</Link>
                </div>
                {flash?.success && <div className="rounded-md border border-green-500/30 bg-green-500/10 px-4 py-3 text-sm text-green-500">{flash.success}</div>}
                <div className="overflow-hidden rounded-lg border">
                    <table className="w-full text-sm">
                        <thead className="bg-muted"><tr>
                            <th className="px-4 py-3 text-left font-medium text-muted-foreground">Title</th>
                            <th className="px-4 py-3 text-left font-medium text-muted-foreground">Category</th>
                            <th className="px-4 py-3 text-left font-medium text-muted-foreground">Views</th>
                            <th className="px-4 py-3" />
                        </tr></thead>
                        <tbody>
                            {articles.data.map(a => (
                                <tr key={a.id} className="border-t hover:bg-muted/50 transition-colors">
                                    <td className="px-4 py-3 font-medium">{a.title}</td>
                                    <td className="px-4 py-3 text-muted-foreground">{a.category?.name || 'N/A'}</td>
                                    <td className="px-4 py-3 text-muted-foreground">{a.views}</td>
                                    <td className="px-4 py-3"><Link href={route('kb.show', a.slug)} className="text-primary hover:underline text-sm">View</Link></td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
